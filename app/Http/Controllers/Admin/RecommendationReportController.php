<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecommendationSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecommendationReportController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $sessions = RecommendationSession::query()
            ->with([
                'student:id,nama,nisn',
                'results' => fn ($rel) => $rel->with('major:id,nama_jurusan')->orderBy('rank'),
            ])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->whereHas('student', function ($sq) use ($q) {
                        $sq->where('nama', 'like', '%'.$q.'%')
                            ->orWhere('nisn', 'like', '%'.$q.'%');
                    })->orWhereHas('results.major', function ($mq) use ($q) {
                        $mq->where('nama_jurusan', 'like', '%'.$q.'%');
                    });
                });
            })
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.hasil-rekomendasi.index', [
            'sessions' => $sessions,
            'q'        => $q,
        ]);
    }
}
