<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingLog extends Model
{
    protected $fillable = [
        'model_id',
        'accuracy',
        'precision',
        'recall',
        'f1_score',
        'status',
        'message',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'accuracy'    => 'decimal:4',
            'precision'   => 'decimal:4',
            'recall'      => 'decimal:4',
            'f1_score'    => 'decimal:4',
            'started_at'  => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<MlModel, $this>
     */
    public function mlModel(): BelongsTo
    {
        return $this->belongsTo(MlModel::class, 'model_id');
    }
}
