<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingDataset;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingDatasetController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $datasets = TrainingDataset::query()
            ->with([
                'criteria:id,nama_kriteria',
                'major:id,nama_jurusan',
            ])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->whereHas('criteria', function ($cq) use ($q) {
                        $cq->where('nama_kriteria', 'like', '%'.$q.'%');
                    })->orWhereHas('major', function ($mq) use ($q) {
                        $mq->where('nama_jurusan', 'like', '%'.$q.'%');
                    });
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.dataset-training.index', [
            'datasets' => $datasets,
            'q'        => $q,
        ]);
    }
}
