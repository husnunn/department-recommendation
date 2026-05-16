<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicScore extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'nilai',
        'is_required',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'nilai'       => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
