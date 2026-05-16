<?php
// File: database/seeders/MajorSeeder.php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $majors = [
            [
                'nama_jurusan' => 'Teknik Informatika',
                'deskripsi' => 'Mempelajari pemrograman, algoritma, database, jaringan, kecerdasan buatan, dan pengembangan perangkat lunak.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Sistem Informasi',
                'deskripsi' => 'Mempelajari penerapan teknologi informasi untuk mendukung proses bisnis dan pengambilan keputusan organisasi.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Data Science',
                'deskripsi' => 'Mempelajari pengolahan data, statistik, machine learning, visualisasi data, dan analisis prediktif.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Psikologi',
                'deskripsi' => 'Mempelajari perilaku manusia, proses mental, konseling, pengembangan diri, dan hubungan sosial.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Pendidikan Bahasa Inggris',
                'deskripsi' => 'Mempelajari bahasa Inggris, pendidikan, komunikasi, linguistik, dan metode pembelajaran bahasa.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Ilmu Komunikasi',
                'deskripsi' => 'Mempelajari komunikasi publik, media, jurnalistik, public relations, dan strategi komunikasi.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Manajemen',
                'deskripsi' => 'Mempelajari pengelolaan organisasi, sumber daya manusia, pemasaran, keuangan, dan operasional bisnis.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Akuntansi',
                'deskripsi' => 'Mempelajari pencatatan keuangan, audit, perpajakan, laporan keuangan, dan sistem informasi akuntansi.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Kedokteran',
                'deskripsi' => 'Mempelajari ilmu kesehatan, anatomi, fisiologi, diagnosis, pengobatan, dan pelayanan medis.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Keperawatan',
                'deskripsi' => 'Mempelajari pelayanan keperawatan, kesehatan pasien, komunikasi medis, dan praktik klinis.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Teknik Sipil',
                'deskripsi' => 'Mempelajari perancangan bangunan, struktur, konstruksi, transportasi, dan manajemen proyek infrastruktur.',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Desain Komunikasi Visual',
                'deskripsi' => 'Mempelajari desain grafis, ilustrasi, branding, multimedia, UI/UX, dan komunikasi visual.',
                'is_active' => true,
            ],
        ];

        foreach ($majors as $major) {
            Major::updateOrCreate(
                ['nama_jurusan' => $major['nama_jurusan']],
                $major
            );
        }
    }
}