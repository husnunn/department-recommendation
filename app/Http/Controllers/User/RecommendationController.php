<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RecommendationSession;
use App\Services\Recommendation\RecommendationService;
use App\Services\User\StudentOnboardingStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly RecommendationService $recommendationService,
        private readonly StudentOnboardingStatus $onboardingStatus,
    ) {}

    public function index(): Response|RedirectResponse
    {
        $user = Auth::user();
        $student = $user->student;
        if ($student === null) {
            return redirect()->route('user.dashboard');
        }

        $errors = $this->recommendationService->prePredictionValidationErrors($student);
        $onboarding = $this->onboardingStatus->forStudent($student);

        return Inertia::render('User/RecommendationsIndex', [
            'canProcess' => $errors === [],
            'validationErrors' => $errors,
            'onboarding' => $onboarding,
            'nextStepLinks' => $this->onboardingStatus->nextStepLinks(),
        ]);
    }

    public function process(): RedirectResponse
    {
        $user = Auth::user();
        $student = $user->student;
        if ($student === null) {
            return redirect()->route('user.dashboard')->withErrors([
                'sesi' => ['Profil siswa tidak ditemukan.'],
            ]);
        }

        $session = RecommendationSession::query()
            ->where('student_id', $student->id)
            ->where('status', 'draft')
            ->latest('id')
            ->first();

        if ($session === null) {
            return redirect()
                ->route('user.recommendations.index')
                ->withErrors([
                    'sesi' => ['Tidak ada sesi kuesioner. Buka halaman tes dan kirim jawaban terlebih dahulu.'],
                ]);
        }

        $outcome = $this->recommendationService->processQuestionnaireSubmit(
            $student->fresh(),
            $session->fresh()
        );

        if (! $outcome->success) {
            return redirect()
                ->route('user.recommendations.index')
                ->withErrors($outcome->errors);
        }

        return redirect()->route('user.hasil', ['sessionId' => $outcome->hasilSessionId]);
    }
}
