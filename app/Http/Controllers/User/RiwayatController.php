<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RecommendationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RiwayatController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $sessions = $student
            ? $student->recommendationSessions()
                ->with(['results' => function ($q) {
                    $q->where('rank', 1)->with('major:id,nama_jurusan');
                }])
                ->latest()
                ->get()
                ->map(fn ($s) => [
                    'id'          => $s->id,
                    'status'      => $s->status,
                    'started_at'  => $s->started_at?->toISOString(),
                    'finished_at' => $s->finished_at?->toISOString(),
                    'created_at'  => $s->created_at->toISOString(),
                    'failure_message' => $s->failure_message,
                    'top_result'  => $s->results->first() ? [
                        'major_name' => $s->results->first()->major->nama_jurusan,
                        'score'      => $s->results->first()->score,
                    ] : null,
                ])
            : [];

        return Inertia::render('User/Riwayat', [
            'sessions' => $sessions,
        ]);
    }
}
