<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'admin@edupath.id',
                'password' => Hash::make('superadmin123'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]
        );
    }
}
