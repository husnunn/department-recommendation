<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCriteriaRequest extends FormRequest
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
            'nama_kriteria' => ['required', 'string', 'max:255', 'unique:criteria,nama_kriteria'],
            'deskripsi'     => ['nullable', 'string'],
            'kategori'      => ['required', Rule::in(['minat', 'bakat', 'lainnya'])],
        ];
    }
}
