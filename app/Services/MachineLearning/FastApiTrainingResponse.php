<?php

namespace App\Services\MachineLearning;

/**
 * Response training dari FastAPI.
 *
 * @phpstan-type ModelMeta array{model_name: string, algorithm?: string|null, version: string, model_path?: string|null}
 * @phpstan-type Metrics array{accuracy?: float|null, precision?: float|null, recall?: float|null, f1_score?: float|null}
 */
final readonly class FastApiTrainingResponse
{
    /**
     * @param  ModelMeta|null  $model
     * @param  Metrics|null  $metrics
     */
    public function __construct(
        public bool $success,
        public ?string $message,
        public ?array $model,
        public ?array $metrics,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromHttpJson(array $payload): self
    {
        $success = (bool) ($payload['success'] ?? false);
        $message = isset($payload['message']) ? (string) $payload['message'] : null;

        $model = null;
        if (isset($payload['model']) && is_array($payload['model'])) {
            $m = $payload['model'];
            $model = [
                'model_name' => (string) ($m['model_name'] ?? 'Random Forest'),
                'algorithm' => isset($m['algorithm']) ? (string) $m['algorithm'] : null,
                'version' => (string) ($m['version'] ?? ''),
                'model_path' => isset($m['model_path']) ? (string) $m['model_path'] : null,
            ];
        }

        $metrics = null;
        if (isset($payload['metrics']) && is_array($payload['metrics'])) {
            $x = $payload['metrics'];
            $metrics = [
                'accuracy' => isset($x['accuracy']) ? (float) $x['accuracy'] : null,
                'precision' => isset($x['precision']) ? (float) $x['precision'] : null,
                'recall' => isset($x['recall']) ? (float) $x['recall'] : null,
                'f1_score' => isset($x['f1_score']) ? (float) $x['f1_score'] : null,
            ];
        }

        return new self($success, $message, $model, $metrics);
    }

    /**
     * @param  ModelMeta  $model
     * @param  Metrics  $metrics
     */
    public static function ok(array $model, array $metrics): self
    {
        return new self(true, null, $model, $metrics);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message, null, null);
    }
}
