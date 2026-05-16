<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'nama_kriteria',
        'deskripsi',
        'kategori',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'criteria_id');
    }

    /**
     * @return HasMany<TrainingDataset, $this>
     */
    public function trainingDatasets(): HasMany
    {
        return $this->hasMany(TrainingDataset::class, 'criteria_id');
    }
}
