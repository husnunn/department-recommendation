<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\TrainMachineLearningModelJob;
use App\Models\MlModel;
use App\Models\TrainingDataset;
use App\Models\TrainingLog;
use App\Services\MachineLearning\ModelTrainingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrainingModelController extends Controller
{
    public function __construct(
        private readonly ModelTrainingService $modelTrainingService,
    ) {}

    public function index(): View
    {
        $datasetCount = TrainingDataset::query()->count();
        $activeModel = MlModel::query()->where('is_active', true)->first();
        $models = MlModel::query()
            ->orderByDesc('is_active')
            ->orderByDesc('trained_at')
            ->orderByDesc('id')
            ->limit(10)
            ->get(['id', 'model_name', 'algorithm', 'version', 'trained_at', 'is_active']);

        $recentLogs = TrainingLog::query()
            ->with(['mlModel:id,model_name,version'])
            ->latest('started_at')
            ->limit(8)
            ->get();

        return view('admin.training-model.index', [
            'datasetCount' => $datasetCount,
            'activeModel'  => $activeModel,
            'models'       => $models,
            'recentLogs'   => $recentLogs,
        ]);
    }

    public function store(): RedirectResponse
    {
        if ($message = $this->modelTrainingService->preflightDispatchMessage()) {
            return redirect()
                ->route('admin.training-model')
                ->with('error', $message);
        }

        TrainMachineLearningModelJob::dispatch();

        return redirect()
            ->route('admin.training-model')
            ->with('success', 'Training model dijadwalkan. Muat ulang halaman setelah beberapa saat untuk melihat model dan log terbaru.');
    }
}
