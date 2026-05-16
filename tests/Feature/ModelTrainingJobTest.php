<?php

namespace Tests\Feature;

use App\Jobs\TrainMachineLearningModelJob;
use App\Models\MlModel;
use App\Models\TrainingDataset;
use Database\Seeders\CriteriaSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\TrainingDatasetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModelTrainingJobTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function training_job_creates_active_model_when_stub_enabled(): void
    {
        config(['ml.stub' => true]);

        $this->seed([
            MajorSeeder::class,
            CriteriaSeeder::class,
            TrainingDatasetSeeder::class,
        ]);

        $this->assertGreaterThan(0, TrainingDataset::query()->count());

        Bus::dispatchSync(new TrainMachineLearningModelJob());

        $this->assertSame(1, MlModel::query()->where('is_active', true)->count());
        $this->assertGreaterThan(0, MlModel::query()->count());
    }
}
