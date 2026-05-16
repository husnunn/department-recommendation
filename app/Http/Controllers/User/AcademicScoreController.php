<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreAcademicScoresRequest;
use App\Models\Subject;
use App\Services\Recommendation\RecommendationPayloadBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AcademicScoreController extends Controller
{
    public function index(RecommendationPayloadBuilder $payloadBuilder): Response|RedirectResponse
    {
        $user = Auth::user();
        $student = $user->student;

        if ($student === null) {
            return redirect()->route('user.profil');
        }

        if (! $payloadBuilder->profileNameIsComplete($student)) {
            return redirect()
                ->route('user.profil')
                ->with('error', 'Lengkapi nama di profil terlebih dahulu.');
        }

        if (! $payloadBuilder->questionnaireIsComplete($student)) {
            return redirect()
                ->route('user.tes')
                ->with('error', 'Selesaikan kuesioner terlebih dahulu sebelum mengisi nilai akademik.');
        }

        $subjects = Subject::query()
            ->where('is_active', true)
            ->orderByDesc('is_required')
            ->orderBy('nama_mapel')
            ->get(['id', 'nama_mapel', 'is_required']);

        $academicScores = $student->academicScores()->pluck('nilai', 'subject_id')->toArray();

        return Inertia::render('User/NilaiAkademik', [
            'subjects' => $subjects,
            'academicScores' => $academicScores,
        ]);
    }

    public function store(StoreAcademicScoresRequest $request): RedirectResponse
    {
        $student = $request->user()->student;
        if ($student === null) {
            return redirect()->route('user.profil')->withErrors([
                'scores' => ['Profil siswa tidak ditemukan.'],
            ]);
        }

        $validated = $request->validated();

        foreach ($validated['scores'] as $subjectId => $nilai) {
            if ($nilai !== null && $nilai !== '') {
                $student->academicScores()->updateOrCreate(
                    ['subject_id' => $subjectId],
                    [
                        'nilai' => $nilai,
                        'is_required' => Subject::query()->find($subjectId)?->is_required ?? false,
                    ]
                );
            }
        }

        return redirect()
            ->route('user.academic-scores.index')
            ->with('success', 'Nilai akademik berhasil disimpan.');
    }
}
