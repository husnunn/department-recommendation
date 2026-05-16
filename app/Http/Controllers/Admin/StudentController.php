<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $students = Student::query()
            ->with(['user:id,username'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('nama', 'like', '%'.$q.'%')
                        ->orWhere('nisn', 'like', '%'.$q.'%')
                        ->orWhere('kelas', 'like', '%'.$q.'%')
                        ->orWhere('asal_sekolah', 'like', '%'.$q.'%');
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('admin.siswa.index', [
            'students' => $students,
            'q'        => $q,
        ]);
    }
}
