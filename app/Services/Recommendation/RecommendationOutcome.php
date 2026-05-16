<?php

namespace App\Services\Recommendation;

/**
 * Hasil operasi pipeline rekomendasi untuk dikonsumsi controller.
 *
 * @phpstan-type ValidationErrors array<string, list<string>>
 */
final readonly class RecommendationOutcome
{
    /**
     * @param  ValidationErrors  $errors
     */
    public function __construct(
        public bool $success,
        public ?int $hasilSessionId = null,
        public array $errors = [],
    ) {}

    /**
     * @param  ValidationErrors  $errors
     */
    public static function validationFailed(array $errors): self
    {
        return new self(success: false, errors: $errors);
    }

    public static function ok(int $sessionId): self
    {
        return new self(success: true, hasilSessionId: $sessionId);
    }
}
