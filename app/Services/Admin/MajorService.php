<?php

namespace App\Services\Admin;

use App\Models\Major;

class MajorService
{
    public function createMajor(array $data): Major
    {
        return Major::create([
            'nama_jurusan' => $data['nama_jurusan'],
            'deskripsi'    => $data['deskripsi'] ?? null,
            'is_active'    => (bool) ($data['is_active'] ?? true),
        ]);
    }

    public function updateMajor(Major $major, array $data): void
    {
        $major->update([
            'nama_jurusan' => $data['nama_jurusan'],
            'deskripsi'    => $data['deskripsi'] ?? null,
            'is_active'    => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function deleteMajor(Major $major): ?string
    {
        if ($major->recommendationResults()->exists()) {
            return 'Jurusan tidak dapat dihapus karena sudah dipakai di hasil rekomendasi.';
        }

        if ($major->trainingDatasets()->exists()) {
            return 'Jurusan tidak dapat dihapus karena sudah dipakai di dataset training.';
        }

        $major->delete();

        return null;
    }
}
