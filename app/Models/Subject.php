<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'nama_mapel',
        'is_required',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_active'   => 'boolean',
        ];
    }

    /**
     * @return HasMany<AcademicScore, $this>
     */
    public function academicScores(): HasMany
    {
        return $this->hasMany(AcademicScore::class);
    }
}
