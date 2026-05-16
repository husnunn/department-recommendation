<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolClassRequest;
use App\Http\Requests\Admin\UpdateSchoolClassRequest;
use App\Models\School;
use App\Models\SchoolClass;
use App\Services\Admin\SchoolClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();
        $schoolId = $request->integer('school_id') ?: null;

        $classes = SchoolClass::query()
            ->with('school:id,nama_sekolah')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->when($q, fn ($query) => $query->where(function ($inner) use ($q) {
                $inner->where('nama_kelas', 'like', '%'.$q.'%')
                    ->orWhere('jurusan', 'like', '%'.$q.'%')
                    ->orWhereHas('school', fn ($s) => $s->where('nama_sekolah', 'like', '%'.$q.'%'));
            }))
            ->orderBy('nama_kelas')
            ->paginate(15)
            ->withQueryString();

        $schools = School::query()->orderBy('nama_sekolah')->get(['id', 'nama_sekolah']);

        return view('admin.kelas.index', [
            'classes' => $classes,
            'schools' => $schools,
            'q' => $q,
            'schoolId' => $schoolId,
        ]);
    }

    public function create(): View
    {
        $schools = School::query()->where('is_active', true)->orderBy('nama_sekolah')->get();

        return view('admin.kelas.create', compact('schools'));
    }

    public function store(StoreSchoolClassRequest $request, SchoolClassService $schoolClassService): RedirectResponse
    {
        $schoolClassService->createSchoolClass($request->validated());

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(SchoolClass $schoolClass): View
    {
        $schoolClass->load('school');

        return view('admin.kelas.show', ['schoolClass' => $schoolClass]);
    }

    public function edit(SchoolClass $schoolClass): View
    {
        $schoolClass->load('school');
        $schools = School::query()->orderBy('nama_sekolah')->get();

        return view('admin.kelas.edit', compact('schoolClass', 'schools'));
    }

    public function update(UpdateSchoolClassRequest $request, SchoolClass $schoolClass, SchoolClassService $schoolClassService): RedirectResponse
    {
        $schoolClassService->updateSchoolClass($schoolClass, $request->validated());

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $schoolClass, SchoolClassService $schoolClassService): RedirectResponse
    {
        $error = $schoolClassService->deleteSchoolClass($schoolClass);

        if ($error !== null) {
            return redirect()
                ->route('admin.kelas.index')
                ->with('error', $error);
        }

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
