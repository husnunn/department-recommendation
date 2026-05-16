<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:siswa')
     *
     * Jika user belum login:
     *   - Route admin → redirect ke /admin/login
     *   - Route lainnya → redirect ke /login
     *
     * Jika user login tapi role tidak sesuai → abort 403.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Belum login → redirect ke login page yang sesuai
        if (! $user) {
            $isAdminRoute = in_array('admin', $roles) || in_array('superadmin', $roles);

            if ($isAdminRoute) {
                return redirect()->route('admin.login');
            }

            return redirect('/login');
        }

        // Superadmin selalu punya akses ke semua role admin
        if ($user->isSuperAdmin() && (in_array('admin', $roles) || in_array('superadmin', $roles))) {
            return $next($request);
        }

        if (! in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
