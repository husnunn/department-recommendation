<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use App\Models\Subject;
use App\Services\Admin\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $subjects = Subject::query()
            ->when($q, function ($query) use ($q) {
                $query->where('nama_mapel', 'like', '%'.$q.'%');
            })
            ->orderBy('nama_mapel')
            ->paginate(15)
            ->withQueryString();

        return view('admin.subjects.index', [
            'subjects' => $subjects,
            'q'        => $q,
        ]);
    }

    public function create(): View
    {
        return view('admin.subjects.create');
    }

    public function store(StoreSubjectRequest $request, SubjectService $subjectService): RedirectResponse
    {
        $subjectService->createSubject($request->validated());

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function show(Subject $subject): View
    {
        $subject->loadCount('academicScores');

        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject): View
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject, SubjectService $subjectService): RedirectResponse
    {
        $subjectService->updateSubject($subject, $request->validated());

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject, SubjectService $subjectService): RedirectResponse
    {
        $error = $subjectService->deleteSubject($subject);

        if ($error !== null) {
            return redirect()
                ->route('admin.mapel.index')
                ->with('error', $error);
        }

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
