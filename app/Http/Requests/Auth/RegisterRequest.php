<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Concerns\WithIndonesianValidationMessages;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use WithIndonesianValidationMessages;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nisn') && ! $this->filled('nisn')) {
            $this->merge(['nisn' => null]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:4', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/', 'unique:users,username'],
            'nisn' => ['nullable', 'string', 'max:30', 'unique:students,nisn'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function indonesianMessages(): array
    {
        return [
            'username.regex' => 'Username hanya boleh huruf, angka, garis bawah (_), dan strip (-).',
            'username.min' => 'Username minimal 4 karakter.',
            'username.unique' => 'Username sudah dipakai. Silakan pilih username lain.',
            'nisn.unique' => 'NISN ini sudah terdaftar. Gunakan NISN lain atau daftar tanpa NISN.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ];
    }
}
