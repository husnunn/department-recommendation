<?php

namespace App\Services\Admin;

use App\Models\SchoolClass;
use App\Models\Student;

class SchoolClassService
{
    public function createSchoolClass(array $data): SchoolClass
    {
        return SchoolClass::create([
            'school_id' => $data['school_id'],
            'nama_kelas' => $data['nama_kelas'],
            'jurusan' => $data['jurusan'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);
    }

    public function updateSchoolClass(SchoolClass $schoolClass, array $data): void
    {
        $schoolClass->update([
            'school_id' => $data['school_id'],
            'nama_kelas' => $data['nama_kelas'],
            'jurusan' => $data['jurusan'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function deleteSchoolClass(SchoolClass $schoolClass): ?string
    {
        if (Student::query()->where('kelas', $schoolClass->nama_kelas)->exists()) {
            return 'Kelas tidak dapat dihapus karena masih dipakai data siswa.';
        }

        $schoolClass->delete();

        return null;
    }
}
