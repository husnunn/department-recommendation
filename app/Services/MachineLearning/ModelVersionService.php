<?php

namespace App\Services\MachineLearning;

use App\Models\MlModel;
use Illuminate\Support\Facades\DB;

class ModelVersionService
{
    /**
     * Hanya satu model aktif (RULES_PERHITUNGAN §18).
     */
    public function setActiveModel(MlModel $model): void
    {
        DB::transaction(function () use ($model): void {
            MlModel::query()->where('id', '!=', $model->id)->update(['is_active' => false]);
            $model->update(['is_active' => true]);
        });
    }
}
