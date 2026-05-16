<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Major extends Model
{
    protected $fillable = [
        'nama_jurusan',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<TrainingDataset, $this>
     */
    public function trainingDatasets(): HasMany
    {
        return $this->hasMany(TrainingDataset::class, 'jurusan_id');
    }

    /**
     * @return HasMany<RecommendationResult, $this>
     */
    public function recommendationResults(): HasMany
    {
        return $this->hasMany(RecommendationResult::class, 'major_id');
    }
}
