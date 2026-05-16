<?php

namespace App\Services\Admin;

use App\Models\Criteria;

class CriteriaService
{
    public function createCriteria(array $data): Criteria
    {
        return Criteria::create([
            'nama_kriteria' => $data['nama_kriteria'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'kategori'      => $data['kategori'],
        ]);
    }

    public function updateCriteria(Criteria $criteria, array $data): void
    {
        $criteria->update([
            'nama_kriteria' => $data['nama_kriteria'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'kategori'      => $data['kategori'],
        ]);
    }

    public function deleteCriteria(Criteria $criteria): ?string
    {
        if ($criteria->questions()->exists()) {
            return 'Kriteria tidak dapat dihapus karena masih memiliki pertanyaan.';
        }

        if ($criteria->trainingDatasets()->exists()) {
            return 'Kriteria tidak dapat dihapus karena dipakai di dataset training.';
        }

        $criteria->delete();

        return null;
    }
}
