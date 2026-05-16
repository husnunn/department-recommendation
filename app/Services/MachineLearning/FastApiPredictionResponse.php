<?php

namespace App\Services\MachineLearning;

/**
 * Response prediksi dari FastAPI (atau setara dari stub lokal).
 *
 * @phpstan-type ResultRow array{rank: int, major_id: int, score: float}
 */
final readonly class FastApiPredictionResponse
{
    /**
     * @param  list<ResultRow>  $results
     * @param  array<string, mixed>|null  $modelMeta
     */
    public function __construct(
        public bool $success,
        public ?string $message,
        public ?array $modelMeta,
        public array $results,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromHttpJson(array $payload): self
    {
        $success = (bool) ($payload['success'] ?? false);
        $message = isset($payload['message']) ? (string) $payload['message'] : null;
        $modelMeta = isset($payload['model']) && is_array($payload['model'])
            ? $payload['model']
            : null;

        $raw = $payload['results'] ?? [];
        $results = [];
        if (is_array($raw)) {
            foreach ($raw as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $results[] = [
                    'rank' => (int) ($row['rank'] ?? 0),
                    'major_id' => (int) ($row['major_id'] ?? 0),
                    'score' => (float) ($row['score'] ?? 0),
                ];
            }
        }

        return new self($success, $message, $modelMeta, $results);
    }

    /**
     * @param  list<ResultRow>  $results
     * @param  array<string, mixed>|null  $modelMeta
     */
    public static function ok(array $results, ?array $modelMeta = null): self
    {
        return new self(true, null, $modelMeta, $results);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message, null, []);
    }
}
