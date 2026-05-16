<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreQuestionnaireAnswersRequest;
use App\Models\Question;
use App\Models\RecommendationSession;
use App\Services\Recommendation\RecommendationPayloadBuilder;
use App\Services\User\StudentOnboardingStatus;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TesController extends Controller
{
    public function index(RecommendationPayloadBuilder $payloadBuilder, StudentOnboardingStatus $onboardingStatus)
    {
        $questions = Question::with(['criteria', 'options' => function ($q) {
            $q->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($q) => [
                'id'         => $q->id,
                'pertanyaan' => $q->pertanyaan,
                'criteria'   => [
                    'id'            => $q->criteria->id,
                    'nama_kriteria' => $q->criteria->nama_kriteria,
                    'kategori'      => $q->criteria->kategori,
                ],
                'options' => $q->options->map(fn ($o) => [
                    'id'    => $o->id,
                    'opsi'  => $o->opsi,
                    'score' => $o->score,
                ])->values(),
            ]);

        $student = Auth::user()->student;

        if ($student !== null && $questions->isNotEmpty() && ! $payloadBuilder->profileNameIsComplete($student)) {
            return redirect()
                ->route('user.profil')
                ->with('error', 'Lengkapi nama di profil sebelum mengisi kuesioner.');
        }

        $session = null;

        if ($student && $questions->isNotEmpty()) {
            $session = RecommendationSession::query()
                ->where('student_id', $student->id)
                ->where('status', 'draft')
                ->latest('id')
                ->first();

            if ($session === null) {
                $session = RecommendationSession::create([
                    'student_id' => $student->id,
                    'status'     => 'draft',
                ]);
            }
        }

        $existingAnswers = [];
        if ($student !== null) {
            $student->loadMissing('answers');
            foreach ($student->answers as $answer) {
                $existingAnswers[$answer->question_id] = [
                    'question_option_id' => $answer->question_option_id,
                    'answer' => (int) $answer->answer,
                ];
            }
        }

        $onboarding = $onboardingStatus->forStudent($student);

        return Inertia::render('User/Tes', [
            'questions' => $questions,
            'sessionId' => $session?->id,
            'existingAnswers' => $existingAnswers,
            'profileNameComplete' => $onboarding['profileNameComplete'],
            'profileSuggestedComplete' => $onboarding['profileSuggestedComplete'],
        ]);
    }

    public function submit(StoreQuestionnaireAnswersRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $student = $user->student;

        $session = RecommendationSession::where('id', $validated['session_id'])
            ->where('student_id', $student->id)
            ->firstOrFail();

        foreach ($validated['answers'] as $answer) {
            $student->answers()->updateOrCreate(
                ['question_id' => $answer['question_id']],
                [
                    'question_option_id' => $answer['question_option_id'],
                    'answer'             => $answer['answer'],
                ]
            );
        }

        $student->refresh();

        return redirect()
            ->route('user.academic-scores.index')
            ->with('success', 'Jawaban tersimpan. Lengkapi nilai akademik wajib, lalu buka Proses rekomendasi.');
    }
}
