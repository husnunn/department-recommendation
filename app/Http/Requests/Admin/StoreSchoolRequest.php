<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\WithIndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
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
            'nama_sekolah' => ['required', 'string', 'max:255', 'unique:schools,nama_sekolah'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
