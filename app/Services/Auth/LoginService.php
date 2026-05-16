<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginService
{
    /**
     * Proses autentikasi user.
     *
     * @param array $credentials
     * @param bool $remember
     * @param array $allowedRoles Array of roles allowed to login via this service.
     * @return \App\Models\User
     * @throws ValidationException
     */
    public function authenticate(array $credentials, bool $remember = false, array $allowedRoles = []): \App\Models\User
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!empty($allowedRoles) && !in_array($user->role, $allowedRoles)) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Akun ini tidak memiliki akses ke halaman ini.',
                ]);
            }

            return $user;
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau kata sandi salah.',
        ]);
    }
}
