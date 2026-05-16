<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $criteria = $this->route('criteria');

        return [
            'nama_kriteria' => [
                'required',
                'string',
                'max:255',
                Rule::unique('criteria', 'nama_kriteria')->ignore($criteria->id),
            ],
            'deskripsi' => ['nullable', 'string'],
            'kategori'  => ['required', Rule::in(['minat', 'bakat', 'lainnya'])],
        ];
    }
}
