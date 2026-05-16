<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMajorRequest;
use App\Http\Requests\Admin\UpdateMajorRequest;
use App\Models\Major;
use App\Services\Admin\MajorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MajorController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $majors = Major::query()
            ->when($q, function ($query) use ($q) {
                $query->where('nama_jurusan', 'like', '%'.$q.'%');
            })
            ->orderBy('nama_jurusan')
            ->paginate(15)
            ->withQueryString();

        return view('admin.majors.index', [
            'majors' => $majors,
            'q'      => $q,
        ]);
    }

    public function create(): View
    {
        return view('admin.majors.create');
    }

    public function store(StoreMajorRequest $request, MajorService $majorService): RedirectResponse
    {
        $majorService->createMajor($request->validated());

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function show(Major $major): View
    {
        $major->loadCount(['recommendationResults', 'trainingDatasets']);

        return view('admin.majors.show', compact('major'));
    }

    public function edit(Major $major): View
    {
        return view('admin.majors.edit', compact('major'));
    }

    public function update(UpdateMajorRequest $request, Major $major, MajorService $majorService): RedirectResponse
    {
        $majorService->updateMajor($major, $request->validated());

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Major $major, MajorService $majorService): RedirectResponse
    {
        $error = $majorService->deleteMajor($major);

        if ($error !== null) {
            return redirect()
                ->route('admin.jurusan.index')
                ->with('error', $error);
        }

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
