<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Concerns\WithIndonesianValidationMessages;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Langkah 5 ALUR: hanya field profil pada `students`, tanpa nilai akademik.
 */
class UpdateStudentProfileRequest extends FormRequest
{
    use WithIndonesianValidationMessages;

    public function authorize(): bool
    {
        return $this->user()?->isSiswa() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nisn' => $this->filled('nisn') ? trim((string) $this->input('nisn')) : null,
            'kelas' => $this->filled('kelas') ? trim((string) $this->input('kelas')) : null,
            'asal_sekolah' => $this->filled('asal_sekolah') ? trim((string) $this->input('asal_sekolah')) : null,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $studentId = $this->user()?->student?->id;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('students', 'nisn')->ignore($studentId),
            ],
            'kelas' => ['nullable', 'string', 'max:100'],
            'asal_sekolah' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function indonesianMessages(): array
    {
        return [
            'nisn.unique' => 'NISN ini sudah dipakai akun siswa lain. Periksa kembali atau kosongkan jika tidak memiliki NISN.',
        ];
    }
}
