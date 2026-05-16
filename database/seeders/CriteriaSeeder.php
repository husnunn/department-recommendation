<?php
// File: database/seeders/CriteriaSeeder.php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            [
                'nama_kriteria' => 'Logika',
                'deskripsi' => 'Kemampuan berpikir sistematis, memecahkan masalah, memahami pola, dan mengambil keputusan berdasarkan analisis.',
                'kategori' => 'bakat',
            ],
            [
                'nama_kriteria' => 'Sosial',
                'deskripsi' => 'Kemampuan berinteraksi, bekerja sama, memahami orang lain, dan membantu lingkungan sekitar.',
                'kategori' => 'minat',
            ],
            [
                'nama_kriteria' => 'Bahasa',
                'deskripsi' => 'Kemampuan memahami, menggunakan, dan menyampaikan informasi melalui bahasa lisan maupun tulisan.',
                'kategori' => 'bakat',
            ],
            [
                'nama_kriteria' => 'Kreativitas',
                'deskripsi' => 'Kemampuan menghasilkan ide baru, membuat karya, menyusun konsep visual, dan berpikir inovatif.',
                'kategori' => 'minat',
            ],
            [
                'nama_kriteria' => 'Analitis',
                'deskripsi' => 'Kemampuan menganalisis data, membandingkan informasi, melihat hubungan sebab-akibat, dan menyusun kesimpulan.',
                'kategori' => 'bakat',
            ],
        ];

        foreach ($criteria as $item) {
            Criteria::updateOrCreate(
                ['nama_kriteria' => $item['nama_kriteria']],
                $item
            );
        }
    }
}