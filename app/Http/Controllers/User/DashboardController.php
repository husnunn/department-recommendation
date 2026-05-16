<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\StudentOnboardingStatus;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private readonly StudentOnboardingStatus $onboardingStatus,
    ) {}

    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        $onboarding = $this->onboardingStatus->forStudent($student);
        $lastRecommendation = $this->onboardingStatus->lastCompletedRecommendation($student);

        $totalTes = $student
            ? $student->recommendationSessions()->where('status', 'completed')->count()
            : 0;

        $lastSession = $student
            ? $student->recommendationSessions()
                ->where('status', 'completed')
                ->latest('finished_at')
                ->first()
            : null;

        $recommendedRoute = $onboarding['recommendedNextRoute'];
        $recommendedUrl = $recommendedRoute === 'user.hasil' && $lastSession
            ? route('user.hasil', ['sessionId' => $lastSession->id])
            : route($recommendedRoute);

        return Inertia::render('User/Dashboard', [
            'student' => $student ? [
                'nama' => $student->nama,
                'kelas' => $student->kelas,
                'asal_sekolah' => $student->asal_sekolah,
                'nisn' => $student->nisn,
            ] : null,
            'stats' => [
                'totalTes' => $totalTes,
                'lastTestDate' => $lastSession?->finished_at?->format('d M Y'),
            ],
            'onboarding' => $onboarding,
            'lastRecommendation' => $lastRecommendation,
            'recommendedUrl' => $recommendedUrl,
        ]);
    }
}
