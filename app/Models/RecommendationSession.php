<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecommendationSession extends Model
{
    protected $fillable = [
        'student_id',
        'status',
        'started_at',
        'finished_at',
        'failure_message',
    ];

    protected function casts(): array
    {
        return [
            'started_at'  => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(RecommendationResult::class, 'session_id');
    }
}
