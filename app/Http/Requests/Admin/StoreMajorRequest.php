<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMajorRequest extends FormRequest
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
        return [
            'nama_jurusan' => ['required', 'string', 'max:255', 'unique:majors,nama_jurusan'],
            'deskripsi'    => ['nullable', 'string'],
            'is_active'    => ['sometimes', 'boolean'],
        ];
    }
}
