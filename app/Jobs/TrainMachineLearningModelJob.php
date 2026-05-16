<?php

namespace App\Jobs;

use App\Services\MachineLearning\ModelTrainingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TrainMachineLearningModelJob implements ShouldQueue
{
    use Queueable;

    public function handle(ModelTrainingService $modelTrainingService): void
    {
        $modelTrainingService->run();
    }
}
