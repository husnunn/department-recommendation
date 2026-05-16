<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MlModel;
use App\Services\MachineLearning\ModelVersionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MlModelsController extends Controller
{
    public function index(): View
    {
        $models = MlModel::query()
            ->orderByDesc('is_active')
            ->orderByDesc('trained_at')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.ml-models.index', [
            'models' => $models,
        ]);
    }

    public function activate(MlModel $mlModel, ModelVersionService $modelVersionService): RedirectResponse
    {
        $modelVersionService->setActiveModel($mlModel);

        return redirect()
            ->route('admin.ml-models')
            ->with('success', 'Model '.$mlModel->model_name.' v'.$mlModel->version.' diaktifkan.');
    }
}
