<?php
// File: database/seeders/SubjectSeeder.php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'nama_mapel' => 'Matematika',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'IPA',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'IPS',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Fisika',
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Kimia',
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Biologi',
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Ekonomi',
                'is_required' => false,
                'is_active' => true,
            ],
            [
                'nama_mapel' => 'Informatika',
                'is_required' => false,
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['nama_mapel' => $subject['nama_mapel']],
                $subject
            );
        }
    }
}