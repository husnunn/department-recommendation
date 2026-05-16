<?php

namespace Database\Factories;

use App\Models\MlModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MlModel>
 */
class MlModelFactory extends Factory
{
    protected $model = MlModel::class;

    public function definition(): array
    {
        return [
            'model_name' => 'Random Forest',
            'algorithm' => 'random_forest',
            'version' => 'v'.now()->format('YmdHis').'-'.substr(sha1((string) uniqid('', true)), 0, 8),
            'model_path' => 'storage/ml-models/test.joblib',
            'trained_at' => now(),
            'is_active' => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => true,
        ]);
    }
}
