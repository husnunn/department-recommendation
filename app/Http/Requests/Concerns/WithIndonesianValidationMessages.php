<?php

namespace App\Http\Requests\Concerns;

trait WithIndonesianValidationMessages
{
    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return array_merge($this->defaultIndonesianAttributes(), $this->indonesianAttributes());
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge($this->defaultIndonesianMessages(), $this->indonesianMessages());
    }

    /**
     * @return array<string, string>
     */
    protected function defaultIndonesianAttributes(): array
    {
        return [
            'nama' => 'nama lengkap',
            'nama_sekolah' => 'nama sekolah',
            'nama_kelas' => 'nama kelas',
            'nama_jurusan' => 'nama jurusan',
            'nama_mapel' => 'nama mata pelajaran',
            'nama_kriteria' => 'nama kriteria',
            'nisn' => 'NISN',
            'username' => 'username',
            'password' => 'kata sandi',
            'kelas' => 'kelas',
            'asal_sekolah' => 'asal sekolah',
            'school_id' => 'sekolah',
            'lokasi' => 'lokasi',
            'jurusan' => 'jurusan',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function defaultIndonesianMessages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max.string' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar. Gunakan nilai lain atau hubungi admin.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'integer' => ':attribute tidak valid.',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function indonesianAttributes(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    protected function indonesianMessages(): array
    {
        return [];
    }
}
