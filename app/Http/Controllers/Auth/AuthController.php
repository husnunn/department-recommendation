<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Proses login.
     */
    public function login(\App\Http\Requests\Auth\LoginRequest $request, \App\Services\Auth\LoginService $service)
    {
        $user = $service->authenticate($request->validated(), $request->boolean('remember'));

        $request->session()->regenerate();

        // Redirect berdasarkan role
        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/user/dashboard');
    }

    /**
     * Tampilkan halaman register.
     */
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Proses registrasi siswa.
     */
    public function register(\App\Http\Requests\Auth\RegisterRequest $request, \App\Services\Auth\RegisterStudentService $service)
    {
        $user = $service->register($request->validated());

        Auth::login($user);

        return redirect('/user/dashboard');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
