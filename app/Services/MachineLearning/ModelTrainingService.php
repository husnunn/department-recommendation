<?php

namespace App\Services\MachineLearning;

use App\Models\MlModel;
use App\Models\TrainingDataset;
use App\Models\TrainingLog;
use App\Services\Recommendation\RecommendationPayloadBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ModelTrainingService
{
    public function __construct(
        private readonly FastApiClient $fastApiClient,
        private readonly ModelVersionService $modelVersionService,
    ) {}

    /**
     * Validasi singkat sebelum job training diantrekan.
     */
    public function preflightDispatchMessage(): ?string
    {
        return $this->validateTrainingDatasets();
    }

    /**
     * Menjalankan training: stub, atau HTTP ke FastAPI, lalu simpan `ml_models` + `training_logs`.
     */
    public function run(): void
    {
        $startedAt = now();

        if ($message = $this->validateTrainingDatasets()) {
            Log::warning('Training ML dibatalkan', ['message' => $message]);
            $this->recordFailedTrainingRun($startedAt, $message);

            return;
        }

        $datasetsPayload = $this->buildTrainingPayload();

        try {
            if (config('ml.stub')) {
                $this->persistSuccessfulStubRun($startedAt);

                return;
            }

            if (rtrim((string) config('ml.base_url'), '/') === '') {
                $this->recordFailedTrainingRun($startedAt, 'URL layanan ML tidak dikonfigurasi.');

                return;
            }

            $response = $this->fastApiClient->train($datasetsPayload);

            if (! $response->success || $response->model === null) {
                $this->recordFailedTrainingRun($startedAt, $response->message ?? 'Training gagal.');

                return;
            }

            $this->persistSuccessfulRemoteRun($startedAt, $response);
        } catch (Throwable $e) {
            Log::error('Training ML exception', ['exception' => $e]);
            $this->recordFailedTrainingRun($startedAt, 'Terjadi kesalahan saat training.');
        }
    }

    public function validateTrainingDatasets(): ?string
    {
        $count = TrainingDataset::query()->count();
        if ($count === 0) {
            return 'Dataset training kosong. Tidak dapat melatih model.';
        }

        $distinctMajors = (int) TrainingDataset::query()
            ->selectRaw('count(distinct jurusan_id) as aggregate')
            ->value('aggregate');

        if ($distinctMajors < 2) {
            return 'Dataset harus memiliki minimal dua jurusan berbeda sebagai label.';
        }

        if ($this->hasIncompleteFeatureRows()) {
            return 'Terdapat baris dataset dengan fitur tidak lengkap (skor kriteria atau nilai mapel).';
        }

        return null;
    }

    private function hasIncompleteFeatureRows(): bool
    {
        return TrainingDataset::query()
            ->where(function ($q): void {
                $q->whereNull('score_logika')
                    ->orWhereNull('score_sosial')
                    ->orWhereNull('score_bahasa')
                    ->orWhereNull('score_kreativitas')
                    ->orWhereNull('score_analitis')
                    ->orWhereNull('nilai_mtk')
                    ->orWhereNull('nilai_bindo')
                    ->orWhereNull('nilai_bing')
                    ->orWhereNull('nilai_ipa')
                    ->orWhereNull('nilai_ips');
            })
            ->exists();
    }

    /**
     * @return array<string, mixed>
     */
    private function buildTrainingPayload(): array
    {
        $rows = [];
        foreach (TrainingDataset::query()->cursor() as $dataset) {
            $features = $dataset->featureVector();
            if ($features === null) {
                continue;
            }
            $rows[] = [
                'features' => $features,
                'jurusan_id' => $dataset->jurusan_id,
            ];
        }

        return [
            'datasets' => $rows,
            'feature_order' => RecommendationPayloadBuilder::FEATURE_KEYS_ORDER,
            'algorithm' => 'random_forest',
        ];
    }

    private function persistSuccessfulStubRun(\DateTimeInterface $startedAt): void
    {
        $version = $this->generateUniqueVersion();

        $modelMeta = [
            'model_name' => 'Random Forest',
            'algorithm' => 'random_forest',
            'version' => $version,
            'model_path' => 'storage/ml-models/stub-'.$version.'.joblib',
        ];

        $metrics = [
            'accuracy' => 0.86,
            'precision' => 0.84,
            'recall' => 0.83,
            'f1_score' => 0.835,
        ];

        $this->persistModelAndLog($startedAt, $modelMeta, $metrics, 'success', null);
    }

    private function persistSuccessfulRemoteRun(\DateTimeInterface $startedAt, FastApiTrainingResponse $response): void
    {
        $m = $response->model;
        if ($m === null) {
            return;
        }

        $metrics = $response->metrics ?? [];

        $this->persistModelAndLog(
            $startedAt,
            [
                'model_name' => $m['model_name'],
                'algorithm' => $m['algorithm'] ?? 'random_forest',
                'version' => $m['version'],
                'model_path' => $m['model_path'] ?? null,
            ],
            [
                'accuracy' => $metrics['accuracy'] ?? null,
                'precision' => $metrics['precision'] ?? null,
                'recall' => $metrics['recall'] ?? null,
                'f1_score' => $metrics['f1_score'] ?? null,
            ],
            'success',
            null,
        );
    }

    /**
     * @param  array{model_name: string, algorithm: string|null, version: string, model_path: string|null}  $modelMeta
     * @param  array{accuracy: mixed, precision: mixed, recall: mixed, f1_score: mixed}  $metrics
     */
    private function persistModelAndLog(
        \DateTimeInterface $startedAt,
        array $modelMeta,
        array $metrics,
        string $status,
        ?string $message,
    ): void {
        DB::transaction(function () use ($startedAt, $modelMeta, $metrics, $status, $message): void {
            $model = MlModel::query()->create([
                'model_name' => $modelMeta['model_name'],
                'algorithm' => $modelMeta['algorithm'],
                'version' => $modelMeta['version'],
                'model_path' => $modelMeta['model_path'],
                'trained_at' => now(),
                'is_active' => false,
            ]);

            TrainingLog::query()->create([
                'model_id' => $model->id,
                'accuracy' => $metrics['accuracy'] ?? null,
                'precision' => $metrics['precision'] ?? null,
                'recall' => $metrics['recall'] ?? null,
                'f1_score' => $metrics['f1_score'] ?? null,
                'status' => $status,
                'message' => $message,
                'started_at' => $startedAt,
                'finished_at' => now(),
            ]);

            if ($status === 'success') {
                $this->modelVersionService->setActiveModel($model);
            }
        });
    }

    private function recordFailedTrainingRun(\DateTimeInterface $startedAt, string $message): void
    {
        DB::transaction(function () use ($startedAt, $message): void {
            $version = $this->generateUniqueVersion().'-failed';

            $model = MlModel::query()->create([
                'model_name' => 'Random Forest',
                'algorithm' => 'random_forest',
                'version' => $version,
                'model_path' => null,
                'trained_at' => null,
                'is_active' => false,
            ]);

            TrainingLog::query()->create([
                'model_id' => $model->id,
                'accuracy' => null,
                'precision' => null,
                'recall' => null,
                'f1_score' => null,
                'status' => 'failed',
                'message' => $message,
                'started_at' => $startedAt,
                'finished_at' => now(),
            ]);
        });
    }

    private function generateUniqueVersion(): string
    {
        $base = 'v'.now()->format('YmdHis');

        if (! MlModel::query()->where('version', $base)->exists()) {
            return $base;
        }

        return $base.'-'.substr(sha1((string) microtime(true)), 0, 6);
    }
}
