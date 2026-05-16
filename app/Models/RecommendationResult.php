<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationResult extends Model
{
    protected $fillable = [
        'session_id',
        'major_id',
        'rank',
        'score',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(RecommendationSession::class, 'session_id');
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}
