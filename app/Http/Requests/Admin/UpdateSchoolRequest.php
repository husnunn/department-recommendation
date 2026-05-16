<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\WithIndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolRequest extends FormRequest
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
        /** @var \App\Models\School $school */
        $school = $this->route('school');

        return [
            'nama_sekolah' => [
                'required',
                'string',
                'max:255',
                Rule::unique('schools', 'nama_sekolah')->ignore($school->id),
            ],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
