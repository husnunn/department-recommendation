<?php

namespace App\Services\Auth;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterStudentService
{
    /**
     * Daftarkan akun siswa baru.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role' => 'siswa',
            ]);

            Student::create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'nisn' => $data['nisn'] ?? null,
            ]);

            return $user;
        });
    }
}
