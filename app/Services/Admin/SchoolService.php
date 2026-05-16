<?php

namespace App\Services\Admin;

use App\Models\School;
use App\Models\Student;

class SchoolService
{
    public function createSchool(array $data): School
    {
        return School::create([
            'nama_sekolah' => $data['nama_sekolah'],
            'lokasi' => $data['lokasi'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);
    }

    public function updateSchool(School $school, array $data): void
    {
        $school->update([
            'nama_sekolah' => $data['nama_sekolah'],
            'lokasi' => $data['lokasi'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function deleteSchool(School $school): ?string
    {
        if ($school->classes()->exists()) {
            return 'Sekolah tidak dapat dihapus karena masih memiliki data kelas. Hapus kelas terlebih dahulu.';
        }

        if (Student::query()->where('asal_sekolah', $school->nama_sekolah)->exists()) {
            return 'Sekolah tidak dapat dihapus karena masih dipakai data siswa.';
        }

        $school->delete();

        return null;
    }
}
