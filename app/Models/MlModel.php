<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MlModel extends Model
{
    /** @use HasFactory<\Database\Factories\MlModelFactory> */
    use HasFactory;

    protected $fillable = [
        'model_name',
        'algorithm',
        'version',
        'model_path',
        'trained_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'trained_at' => 'datetime',
            'is_active'  => 'boolean',
        ];
    }

    /**
     * @return HasMany<TrainingLog, $this>
     */
    public function trainingLogs(): HasMany
    {
        return $this->hasMany(TrainingLog::class, 'model_id');
    }
}
