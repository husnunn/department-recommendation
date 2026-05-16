<?php

namespace App\Services\User;

use App\Models\MlModel;
use App\Models\RecommendationSession;
use App\Models\Student;
use App\Services\Recommendation\RecommendationPayloadBuilder;
use App\Services\Recommendation\RecommendationService;

class StudentOnboardingStatus
{
    public function __construct(
        private readonly RecommendationPayloadBuilder $payloadBuilder,
        private readonly RecommendationService $recommendationService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function forStudent(?Student $student): array
    {
        if ($student === null) {
            return $this->emptyOnboarding();
        }

        $student->loadMissing(['answers', 'academicScores.subject']);

        $validationErrors = $this->recommendationService->prePredictionValidationErrors($student);

        $profileNameComplete = $this->payloadBuilder->profileNameIsComplete($student);
        $questionnaireComplete = $this->payloadBuilder->questionnaireIsComplete($student);
        $academicScoresComplete = ! isset($validationErrors['nilai_akademik']);

        $canProcess = $validationErrors === [];

        return [
            'profileNameComplete' => $profileNameComplete,
            'profileSuggestedComplete' => $this->profileSuggestedComplete($student),
            'questionnaireComplete' => $questionnaireComplete,
            'academicScoresComplete' => $academicScoresComplete,
            'canProcessRecommendation' => $canProcess,
            'validationErrors' => $validationErrors,
            'recommendedNextRoute' => $this->recommendedNextRoute(
                $student,
                $profileNameComplete,
                $questionnaireComplete,
                $academicScoresComplete,
                $canProcess,
            ),
            'recommendedNextLabel' => $this->recommendedNextLabel(
                $profileNameComplete,
                $questionnaireComplete,
                $academicScoresComplete,
                $canProcess,
            ),
            'steps' => [
                'profil' => [
                    'label' => $profileNameComplete ? 'Profil lengkap' : 'Profil belum lengkap',
                    'complete' => $profileNameComplete,
                    'route' => 'user.profil',
                ],
                'kuesioner' => [
                    'label' => $questionnaireComplete ? 'Kuesioner selesai' : 'Kuesioner belum diisi',
                    'complete' => $questionnaireComplete,
                    'route' => 'user.tes',
                ],
                'nilai' => [
                    'label' => $academicScoresComplete ? 'Nilai akademik lengkap' : 'Nilai akademik belum lengkap',
                    'complete' => $academicScoresComplete,
                    'route' => 'user.academic-scores.index',
                ],
                'rekomendasi' => [
                    'label' => $canProcess ? 'Siap diproses' : 'Belum siap diproses',
                    'complete' => $canProcess,
                    'route' => 'user.recommendations.index',
                ],
            ],
        ];
    }

    /**
     * @return array{session: array<string, mixed>, results: list<array{rank: int, score: float, major: array{nama_jurusan: string, deskripsi: string|null}}>}|null
     */
    public function lastCompletedRecommendation(?Student $student): ?array
    {
        if ($student === null) {
            return null;
        }

        $session = RecommendationSession::query()
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->with(['results' => fn ($q) => $q->with('major:id,nama_jurusan,deskripsi')->orderBy('rank')])
            ->latest('finished_at')
            ->first();

        if ($session === null || $session->results->isEmpty()) {
            return null;
        }

        return [
            'session' => [
                'id' => $session->id,
                'finished_at' => $session->finished_at?->toISOString(),
            ],
            'results' => $session->results->map(fn ($r) => [
                'rank' => $r->rank,
                'score' => (float) $r->score,
                'major' => [
                    'nama_jurusan' => $r->major->nama_jurusan,
                    'deskripsi' => $r->major->deskripsi,
                ],
            ])->values()->all(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function nextStepLinks(): array
    {
        return [
            'profil' => route('user.profil'),
            'kuesioner' => route('user.tes'),
            'nilai' => route('user.academic-scores.index'),
            'proses' => route('user.recommendations.index'),
        ];
    }

    private function profileSuggestedComplete(Student $student): bool
    {
        return filled($student->nisn)
            && filled($student->kelas)
            && filled($student->asal_sekolah);
    }

    private function recommendedNextRoute(
        Student $student,
        bool $profileNameComplete,
        bool $questionnaireComplete,
        bool $academicScoresComplete,
        bool $canProcess,
    ): string {
        if (! $profileNameComplete) {
            return 'user.profil';
        }

        if (! $questionnaireComplete) {
            return 'user.tes';
        }

        if (! $academicScoresComplete) {
            return 'user.academic-scores.index';
        }

        if ($canProcess) {
            return 'user.recommendations.index';
        }

        $hasCompleted = RecommendationSession::query()
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->exists();

        if ($hasCompleted) {
            return 'user.hasil';
        }

        return 'user.recommendations.index';
    }

    private function recommendedNextLabel(
        bool $profileNameComplete,
        bool $questionnaireComplete,
        bool $academicScoresComplete,
        bool $canProcess,
    ): string {
        if (! $profileNameComplete) {
            return 'Lengkapi profil';
        }

        if (! $questionnaireComplete) {
            return 'Isi kuesioner';
        }

        if (! $academicScoresComplete) {
            return 'Isi nilai akademik';
        }

        if ($canProcess) {
            return 'Proses rekomendasi';
        }

        if (! MlModel::query()->where('is_active', true)->exists()) {
            return 'Model belum tersedia';
        }

        return 'Periksa kesiapan';
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyOnboarding(): array
    {
        return [
            'profileNameComplete' => false,
            'profileSuggestedComplete' => false,
            'questionnaireComplete' => false,
            'academicScoresComplete' => false,
            'canProcessRecommendation' => false,
            'validationErrors' => ['profil' => ['Profil siswa tidak ditemukan.']],
            'recommendedNextRoute' => 'user.profil',
            'recommendedNextLabel' => 'Lengkapi profil',
            'steps' => [
                'profil' => ['label' => 'Profil belum lengkap', 'complete' => false, 'route' => 'user.profil'],
                'kuesioner' => ['label' => 'Kuesioner belum diisi', 'complete' => false, 'route' => 'user.tes'],
                'nilai' => ['label' => 'Nilai akademik belum lengkap', 'complete' => false, 'route' => 'user.academic-scores.index'],
                'rekomendasi' => ['label' => 'Belum siap diproses', 'complete' => false, 'route' => 'user.recommendations.index'],
            ],
        ];
    }
}
