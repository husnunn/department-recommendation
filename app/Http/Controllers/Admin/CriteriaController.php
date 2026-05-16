<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCriteriaRequest;
use App\Http\Requests\Admin\UpdateCriteriaRequest;
use App\Models\Criteria;
use App\Services\Admin\CriteriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CriteriaController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $criteria = Criteria::query()
            ->withCount('questions')
            ->when($q, function ($query) use ($q) {
                $query->where('nama_kriteria', 'like', '%'.$q.'%');
            })
            ->orderBy('nama_kriteria')
            ->paginate(15)
            ->withQueryString();

        return view('admin.criteria.index', [
            'criteria' => $criteria,
            'q'        => $q,
        ]);
    }

    public function create(): View
    {
        return view('admin.criteria.create');
    }

    public function store(StoreCriteriaRequest $request, CriteriaService $criteriaService): RedirectResponse
    {
        $criteriaService->createCriteria($request->validated());

        return redirect()
            ->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function show(Criteria $criteria): View
    {
        $criteria->loadCount(['questions', 'trainingDatasets']);

        return view('admin.criteria.show', compact('criteria'));
    }

    public function edit(Criteria $criteria): View
    {
        return view('admin.criteria.edit', compact('criteria'));
    }

    public function update(UpdateCriteriaRequest $request, Criteria $criteria, CriteriaService $criteriaService): RedirectResponse
    {
        $criteriaService->updateCriteria($criteria, $request->validated());

        return redirect()
            ->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $criteria, CriteriaService $criteriaService): RedirectResponse
    {
        $error = $criteriaService->deleteCriteria($criteria);

        if ($error !== null) {
            return redirect()
                ->route('admin.kriteria.index')
                ->with('error', $error);
        }

        return redirect()
            ->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}
