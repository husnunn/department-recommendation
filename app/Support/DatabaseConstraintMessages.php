<?php

namespace App\Support;

use Illuminate\Database\QueryException;

class DatabaseConstraintMessages
{
    /**
     * Pesan ramah pengguna untuk pelanggaran unique (MySQL 1062).
     *
     * @return array{field: string, message: string}|null
     */
    public static function resolveDuplicateEntry(QueryException $exception): ?array
    {
        if ((int) ($exception->errorInfo[1] ?? 0) !== 1062) {
            return null;
        }

        $sqlMessage = $exception->getMessage();

        $map = [
            'students_nisn_unique' => [
                'field' => 'nisn',
                'message' => 'NISN ini sudah dipakai akun siswa lain. Periksa kembali atau kosongkan jika tidak memiliki NISN.',
            ],
            'users_username_unique' => [
                'field' => 'username',
                'message' => 'Username sudah dipakai. Silakan pilih username lain.',
            ],
            'schools_nama_sekolah_unique' => [
                'field' => 'nama_sekolah',
                'message' => 'Nama sekolah ini sudah terdaftar.',
            ],
            'school_classes_school_id_nama_kelas_unique' => [
                'field' => 'nama_kelas',
                'message' => 'Nama kelas ini sudah ada di sekolah yang dipilih.',
            ],
            'majors_nama_jurusan_unique' => [
                'field' => 'nama_jurusan',
                'message' => 'Nama jurusan ini sudah terdaftar.',
            ],
            'subjects_nama_mapel_unique' => [
                'field' => 'nama_mapel',
                'message' => 'Nama mata pelajaran ini sudah terdaftar.',
            ],
            'criteria_nama_kriteria_unique' => [
                'field' => 'nama_kriteria',
                'message' => 'Nama kriteria ini sudah terdaftar.',
            ],
        ];

        foreach ($map as $needle => $payload) {
            if (str_contains($sqlMessage, $needle)) {
                return $payload;
            }
        }

        return [
            'field' => 'form',
            'message' => 'Data yang Anda masukkan sudah ada di sistem. Periksa kembali lalu coba lagi.',
        ];
    }
}
