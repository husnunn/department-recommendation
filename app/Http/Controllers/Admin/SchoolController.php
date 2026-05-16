<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolRequest;
use App\Http\Requests\Admin\UpdateSchoolRequest;
use App\Models\School;
use App\Services\Admin\SchoolService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $schools = School::query()
            ->withCount('classes')
            ->when($q, fn ($query) => $query->where(function ($inner) use ($q) {
                $inner->where('nama_sekolah', 'like', '%'.$q.'%')
                    ->orWhere('lokasi', 'like', '%'.$q.'%');
            }))
            ->orderBy('nama_sekolah')
            ->paginate(15)
            ->withQueryString();

        return view('admin.sekolah.index', [
            'schools' => $schools,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('admin.sekolah.create');
    }

    public function store(StoreSchoolRequest $request, SchoolService $schoolService): RedirectResponse
    {
        $schoolService->createSchool($request->validated());

        return redirect()
            ->route('admin.sekolah.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function show(School $school): View
    {
        $school->loadCount('classes');

        return view('admin.sekolah.show', compact('school'));
    }

    public function edit(School $school): View
    {
        return view('admin.sekolah.edit', compact('school'));
    }

    public function update(UpdateSchoolRequest $request, School $school, SchoolService $schoolService): RedirectResponse
    {
        $schoolService->updateSchool($school, $request->validated());

        return redirect()
            ->route('admin.sekolah.index')
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroy(School $school, SchoolService $schoolService): RedirectResponse
    {
        $error = $schoolService->deleteSchool($school);

        if ($error !== null) {
            return redirect()
                ->route('admin.sekolah.index')
                ->with('error', $error);
        }

        return redirect()
            ->route('admin.sekolah.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }
}
