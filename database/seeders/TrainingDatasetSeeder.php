<?php
// File: database/seeders/TrainingDatasetSeeder.php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Major;
use App\Models\TrainingDataset;
use Illuminate\Database\Seeder;

class TrainingDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $datasets = [
            [
                'criteria' => 'Logika',
                'nilai_mtk' => 90,
                'nilai_bindo' => 78,
                'nilai_bing' => 80,
                'nilai_ipa' => 88,
                'nilai_ips' => 72,
                'jurusan' => 'Teknik Informatika',
            ],
            [
                'criteria' => 'Analitis',
                'nilai_mtk' => 88,
                'nilai_bindo' => 80,
                'nilai_bing' => 82,
                'nilai_ipa' => 85,
                'nilai_ips' => 75,
                'jurusan' => 'Sistem Informasi',
            ],
            [
                'criteria' => 'Analitis',
                'nilai_mtk' => 92,
                'nilai_bindo' => 78,
                'nilai_bing' => 84,
                'nilai_ipa' => 89,
                'nilai_ips' => 73,
                'jurusan' => 'Data Science',
            ],
            [
                'criteria' => 'Sosial',
                'nilai_mtk' => 75,
                'nilai_bindo' => 86,
                'nilai_bing' => 82,
                'nilai_ipa' => 76,
                'nilai_ips' => 88,
                'jurusan' => 'Psikologi',
            ],
            [
                'criteria' => 'Bahasa',
                'nilai_mtk' => 72,
                'nilai_bindo' => 90,
                'nilai_bing' => 88,
                'nilai_ipa' => 70,
                'nilai_ips' => 80,
                'jurusan' => 'Pendidikan Bahasa Inggris',
            ],
            [
                'criteria' => 'Bahasa',
                'nilai_mtk' => 74,
                'nilai_bindo' => 88,
                'nilai_bing' => 86,
                'nilai_ipa' => 72,
                'nilai_ips' => 84,
                'jurusan' => 'Ilmu Komunikasi',
            ],
            [
                'criteria' => 'Sosial',
                'nilai_mtk' => 78,
                'nilai_bindo' => 82,
                'nilai_bing' => 80,
                'nilai_ipa' => 74,
                'nilai_ips' => 86,
                'jurusan' => 'Manajemen',
            ],
            [
                'criteria' => 'Analitis',
                'nilai_mtk' => 86,
                'nilai_bindo' => 78,
                'nilai_bing' => 76,
                'nilai_ipa' => 75,
                'nilai_ips' => 88,
                'jurusan' => 'Akuntansi',
            ],
            [
                'criteria' => 'Logika',
                'nilai_mtk' => 89,
                'nilai_bindo' => 80,
                'nilai_bing' => 78,
                'nilai_ipa' => 92,
                'nilai_ips' => 70,
                'jurusan' => 'Kedokteran',
            ],
            [
                'criteria' => 'Sosial',
                'nilai_mtk' => 76,
                'nilai_bindo' => 82,
                'nilai_bing' => 78,
                'nilai_ipa' => 86,
                'nilai_ips' => 80,
                'jurusan' => 'Keperawatan',
            ],
            [
                'criteria' => 'Logika',
                'nilai_mtk' => 88,
                'nilai_bindo' => 76,
                'nilai_bing' => 74,
                'nilai_ipa' => 90,
                'nilai_ips' => 72,
                'jurusan' => 'Teknik Sipil',
            ],
            [
                'criteria' => 'Kreativitas',
                'nilai_mtk' => 74,
                'nilai_bindo' => 84,
                'nilai_bing' => 80,
                'nilai_ipa' => 72,
                'nilai_ips' => 78,
                'jurusan' => 'Desain Komunikasi Visual',
            ],

            // Variasi tambahan agar dataset tidak terlalu sedikit.
            [
                'criteria' => 'Logika',
                'nilai_mtk' => 85,
                'nilai_bindo' => 76,
                'nilai_bing' => 79,
                'nilai_ipa' => 84,
                'nilai_ips' => 70,
                'jurusan' => 'Teknik Informatika',
            ],
            [
                'criteria' => 'Analitis',
                'nilai_mtk' => 84,
                'nilai_bindo' => 80,
                'nilai_bing' => 81,
                'nilai_ipa' => 80,
                'nilai_ips' => 78,
                'jurusan' => 'Sistem Informasi',
            ],
            [
                'criteria' => 'Analitis',
                'nilai_mtk' => 91,
                'nilai_bindo' => 79,
                'nilai_bing' => 83,
                'nilai_ipa' => 86,
                'nilai_ips' => 76,
                'jurusan' => 'Data Science',
            ],
            [
                'criteria' => 'Sosial',
                'nilai_mtk' => 73,
                'nilai_bindo' => 85,
                'nilai_bing' => 80,
                'nilai_ipa' => 75,
                'nilai_ips' => 87,
                'jurusan' => 'Psikologi',
            ],
            [
                'criteria' => 'Bahasa',
                'nilai_mtk' => 70,
                'nilai_bindo' => 91,
                'nilai_bing' => 90,
                'nilai_ipa' => 72,
                'nilai_ips' => 82,
                'jurusan' => 'Pendidikan Bahasa Inggris',
            ],
            [
                'criteria' => 'Kreativitas',
                'nilai_mtk' => 76,
                'nilai_bindo' => 86,
                'nilai_bing' => 82,
                'nilai_ipa' => 74,
                'nilai_ips' => 80,
                'jurusan' => 'Desain Komunikasi Visual',
            ],
        ];

        foreach ($datasets as $item) {
            $criteria = Criteria::where('nama_kriteria', $item['criteria'])->first();
            $major = Major::where('nama_jurusan', $item['jurusan'])->first();

            if (! $criteria || ! $major) {
                continue;
            }

            $scores = TrainingDataset::syntheticScoresFromDominantNama($item['criteria']);

            TrainingDataset::updateOrCreate(
                [
                    'criteria_id' => $criteria->id,
                    'nilai_mtk' => $item['nilai_mtk'],
                    'nilai_bindo' => $item['nilai_bindo'],
                    'nilai_bing' => $item['nilai_bing'],
                    'nilai_ipa' => $item['nilai_ipa'],
                    'nilai_ips' => $item['nilai_ips'],
                    'jurusan_id' => $major->id,
                ],
                array_merge([
                    'criteria_id' => $criteria->id,
                    'nilai_mtk' => $item['nilai_mtk'],
                    'nilai_bindo' => $item['nilai_bindo'],
                    'nilai_bing' => $item['nilai_bing'],
                    'nilai_ipa' => $item['nilai_ipa'],
                    'nilai_ips' => $item['nilai_ips'],
                    'jurusan_id' => $major->id,
                ], $scores)
            );
        }
    }
}