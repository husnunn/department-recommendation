<?php

namespace App\Services\Recommendation;

use App\Models\Major;
use App\Models\MlModel;
use App\Models\RecommendationSession;
use App\Models\Student;
use App\Services\MachineLearning\FastApiClient;
use App\Services\MachineLearning\FastApiPredictionResponse;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    public function __construct(
        private readonly RecommendationPayloadBuilder $payloadBuilder,
        private readonly FastApiClient $fastApiClient,
        private readonly RecommendationResultService $resultService,
    ) {}

    /**
     * Validasi yang sama seperti sebelum prediksi (tanpa cek sesi).
     *
     * @return array<string, list<string>>
     */
    public function prePredictionValidationErrors(Student $student): array
    {
        $errors = $this->payloadBuilder->validationErrors($student);

        if (! config('ml.stub') && rtrim((string) config('ml.base_url'), '/') === '') {
            $errors['layanan_ml'] = [
                'Layanan machine learning belum dikonfigurasi. Set ML_SERVICE_URL atau aktifkan ML_SERVICE_STUB untuk pengembangan lokal.',
            ];
        }

        if (MlModel::query()->where('is_active', true)->doesntExist()) {
            $errors['model_ml'] = [
                'Belum ada model aktif.',
            ];
        }

        return $errors;
    }

    /**
     * Alur utama setelah siswa mengirim jawaban kuesioner (RULES §19, §31).
     */
    public function processQuestionnaireSubmit(Student $student, RecommendationSession $session): RecommendationOutcome
    {
        if ((int) $session->student_id !== (int) $student->id) {
            return RecommendationOutcome::validationFailed([
                'sesi' => ['Sesi tidak valid.'],
            ]);
        }

        if ($session->status !== 'draft') {
            return RecommendationOutcome::validationFailed([
                'sesi' => ['Sesi ini sudah diproses.'],
            ]);
        }

        $errors = $this->prePredictionValidationErrors($student);

        if ($errors !== []) {
            return RecommendationOutcome::validationFailed($errors);
        }

        $model = MlModel::query()->where('is_active', true)->first();
        if ($model === null) {
            return RecommendationOutcome::validationFailed([
                'model_ml' => ['Belum ada model aktif.'],
            ]);
        }

        $payload = $this->payloadBuilder->buildRequestPayload($student);
        $payload['model'] = [
            'model_name' => $model->model_name,
            'algorithm' => $model->algorithm,
            'version' => $model->version,
            'model_path' => $model->model_path,
        ];

        $session->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);

        $prediction = $this->invokePrediction($payload);

        if (! $prediction->success) {
            $this->markSessionFailed($session, $prediction->message ?? 'Prediksi gagal.');

            return RecommendationOutcome::validationFailed([
                'rekomendasi' => [$prediction->message ?? 'Prediksi gagal.'],
            ]);
        }

        $rows = $this->sanitizePredictionRows($prediction->results);
        if ($rows === []) {
            $this->markSessionFailed($session, 'Format hasil prediksi tidak valid.');

            return RecommendationOutcome::validationFailed([
                'rekomendasi' => ['Format hasil prediksi tidak valid.'],
            ]);
        }

        DB::transaction(function () use ($session, $rows): void {
            $this->resultService->replaceResults($session, $rows);
            $session->update([
                'status' => 'completed',
                'finished_at' => now(),
            ]);
        });

        return RecommendationOutcome::ok($session->id);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function invokePrediction(array $payload): FastApiPredictionResponse
    {
        if (config('ml.stub')) {
            $rows = $this->resultService->stubTopMajors();

            return FastApiPredictionResponse::ok($rows, $payload['model'] ?? null);
        }

        return $this->fastApiClient->predict($payload);
    }

    private function markSessionFailed(RecommendationSession $session, ?string $failureMessage = null): void
    {
        $session->update([
            'status' => 'failed',
            'finished_at' => now(),
            'failure_message' => $failureMessage,
        ]);
    }

    /**
     * @param  list<array{rank: int, major_id: int, score: float}>  $raw
     * @return list<array{major_id: int, rank: int, score: float}>
     */
    private function sanitizePredictionRows(array $raw): array
    {
        $byMajor = [];

        foreach ($raw as $row) {
            $majorId = (int) ($row['major_id'] ?? 0);
            if ($majorId < 1) {
                continue;
            }

            if (! Major::query()->whereKey($majorId)->where('is_active', true)->exists()) {
                continue;
            }

            $score = (float) ($row['score'] ?? 0);
            if ($score > 0 && $score <= 1.0) {
                $score *= 100;
            }

            $score = max(0.0, min(100.0, $score));

            if (! isset($byMajor[$majorId]) || $byMajor[$majorId]['score'] < $score) {
                $byMajor[$majorId] = [
                    'major_id' => $majorId,
                    'score' => $score,
                ];
            }
        }

        $list = array_values($byMajor);
        usort($list, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);
        $list = array_slice($list, 0, 3);

        $out = [];
        $rank = 1;
        foreach ($list as $item) {
            $out[] = [
                'major_id' => $item['major_id'],
                'rank' => $rank,
                'score' => $item['score'],
            ];
            $rank++;
        }

        return $out;
    }
}
