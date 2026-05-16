<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $users = User::query()
            ->whereIn('role', ['admin', 'superadmin'])
            ->select(['id', 'username', 'email', 'role', 'created_at'])
            ->when($q, function ($query) use ($q) {
                $query->where('username', 'like', '%'.$q.'%');
            })
            ->orderByRaw("CASE WHEN role = 'superadmin' THEN 0 ELSE 1 END")
            ->orderBy('username')
            ->paginate(15)
            ->withQueryString();

        return view('admin.manajemen-admin.index', [
            'users' => $users,
            'q'     => $q,
        ]);
    }
}
