<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RecommendationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HasilController extends Controller
{
    public function show(int $sessionId)
    {
        $student = Auth::user()->student;

        $session = RecommendationSession::where('id', $sessionId)
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->firstOrFail();

        $results = $session->results()
            ->with('major:id,nama_jurusan,deskripsi')
            ->orderBy('rank')
            ->get()
            ->map(fn ($r) => [
                'rank'  => $r->rank,
                'score' => (float) $r->score,
                'major' => [
                    'nama_jurusan' => $r->major->nama_jurusan,
                    'deskripsi'    => $r->major->deskripsi,
                ],
            ]);

        return Inertia::render('User/HasilRekomendasi', [
            'student' => [
                'nama' => $student->nama,
            ],
            'session' => [
                'id'          => $session->id,
                'status'      => $session->status,
                'finished_at' => $session->finished_at?->toISOString(),
            ],
            'results' => $results,
        ]);
    }
}
