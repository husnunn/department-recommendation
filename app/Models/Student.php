<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nisn',
        'nama',
        'kelas',
        'asal_sekolah',
    ];

    /**
     * Relasi ke user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke jawaban siswa.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }

    /**
     * Relasi ke nilai akademik.
     */
    public function academicScores(): HasMany
    {
        return $this->hasMany(AcademicScore::class);
    }

    /**
     * Relasi ke sesi rekomendasi.
     */
    public function recommendationSessions(): HasMany
    {
        return $this->hasMany(RecommendationSession::class);
    }
}
