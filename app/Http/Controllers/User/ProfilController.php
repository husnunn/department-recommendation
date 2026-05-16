<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateStudentProfileRequest;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        $schools = School::query()
            ->where('is_active', true)
            ->orderBy('nama_sekolah')
            ->get(['id', 'nama_sekolah']);

        $classes = SchoolClass::query()
            ->where('is_active', true)
            ->with('school:id,nama_sekolah')
            ->orderBy('nama_kelas')
            ->get(['id', 'school_id', 'nama_kelas', 'jurusan']);

        return Inertia::render('User/Profil', [
            'student' => $student ? [
                'id'           => $student->id,
                'nama'         => $student->nama,
                'nisn'         => $student->nisn,
                'kelas'        => $student->kelas,
                'asal_sekolah' => $student->asal_sekolah,
            ] : null,
            'schools' => $schools,
            'classes' => $classes->map(fn (SchoolClass $class) => [
                'id' => $class->id,
                'school_id' => $class->school_id,
                'nama_kelas' => $class->nama_kelas,
                'jurusan' => $class->jurusan,
                'school_name' => $class->school->nama_sekolah,
            ])->values(),
        ]);
    }

    public function update(UpdateStudentProfileRequest $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $validated = $request->validated();

        if (! $student) {
            $student = $user->student()->create([
                'nama'         => $validated['nama'],
                'nisn'         => $validated['nisn'] ?? null,
                'kelas'        => $validated['kelas'] ?? null,
                'asal_sekolah' => $validated['asal_sekolah'] ?? null,
            ]);
        } else {
            $student->update([
                'nama' => $validated['nama'],
                'nisn' => $validated['nisn'],
                'kelas' => $validated['kelas'],
                'asal_sekolah' => $validated['asal_sekolah'],
            ]);
        }

        return back()->with('success', 'Profil berhasil disimpan.');
    }
}
