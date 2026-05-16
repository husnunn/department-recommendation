<?php

namespace App\Services\Admin;

use App\Models\Subject;

class SubjectService
{
    public function createSubject(array $data): Subject
    {
        return Subject::create([
            'nama_mapel'  => $data['nama_mapel'],
            'is_required' => (bool) ($data['is_required'] ?? false),
            'is_active'   => (bool) ($data['is_active'] ?? true),
        ]);
    }

    public function updateSubject(Subject $subject, array $data): void
    {
        $subject->update([
            'nama_mapel'  => $data['nama_mapel'],
            'is_required' => (bool) ($data['is_required'] ?? false),
            'is_active'   => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function deleteSubject(Subject $subject): ?string
    {
        if ($subject->academicScores()->exists()) {
            return 'Mata pelajaran tidak dapat dihapus karena sudah dipakai di nilai akademik siswa.';
        }

        $subject->delete();

        return null;
    }
}
