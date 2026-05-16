<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\WithIndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSchoolClassRequest extends FormRequest
{
    use WithIndonesianValidationMessages;

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'nama_kelas' => [
                'required',
                'string',
                'max:100',
                Rule::unique('school_classes', 'nama_kelas')->where('school_id', $this->input('school_id')),
            ],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
