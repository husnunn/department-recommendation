<?php

namespace App\Models;

use App\Services\Recommendation\RecommendationPayloadBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingDataset extends Model
{
    protected $fillable = [
        'criteria_id',
        'score_logika',
        'score_sosial',
        'score_bahasa',
        'score_kreativitas',
        'score_analitis',
        'nilai_mtk',
        'nilai_bindo',
        'nilai_bing',
        'nilai_ipa',
        'nilai_ips',
        'jurusan_id',
    ];

    protected function casts(): array
    {
        return [
            'score_logika'      => 'decimal:4',
            'score_sosial'      => 'decimal:4',
            'score_bahasa'      => 'decimal:4',
            'score_kreativitas' => 'decimal:4',
            'score_analitis'    => 'decimal:4',
            'nilai_mtk'         => 'decimal:2',
            'nilai_bindo'       => 'decimal:2',
            'nilai_bing'        => 'decimal:2',
            'nilai_ipa'         => 'decimal:2',
            'nilai_ips'         => 'decimal:2',
        ];
    }

    /**
     * Skor sintetis per kriteria (0–100) untuk baris dataset yang hanya punya kriteria dominan lama.
     * Kriteria dominan mendapat skor lebih tinggi agar pola label tetap bermakna untuk training.
     *
     * @return array<string, float>
     */
    public static function syntheticScoresFromDominantNama(string $namaKriteria): array
    {
        $out = [];
        foreach (RecommendationPayloadBuilder::CANONICAL_CRITERIA_NAMES as $nama) {
            $key = match ($nama) {
                'Logika' => 'score_logika',
                'Sosial' => 'score_sosial',
                'Bahasa' => 'score_bahasa',
                'Kreativitas' => 'score_kreativitas',
                'Analitis' => 'score_analitis',
                default => 'score_logika',
            };
            $out[$key] = $nama === $namaKriteria
                ? 82.5
                : round(48.0 + (crc32($namaKriteria.$nama) % 28), 4);
        }

        return $out;
    }

    /**
     * Fitur untuk training / inferensi — urutan mengikuti {@see RecommendationPayloadBuilder::FEATURE_KEYS_ORDER}.
     *
     * @return array<string, float>|null null jika data tidak lengkap
     */
    public function featureVector(): ?array
    {
        if ($this->score_logika === null
            || $this->score_sosial === null
            || $this->score_bahasa === null
            || $this->score_kreativitas === null
            || $this->score_analitis === null
            || $this->nilai_mtk === null
            || $this->nilai_bindo === null
            || $this->nilai_bing === null
            || $this->nilai_ipa === null
            || $this->nilai_ips === null
        ) {
            return null;
        }

        return [
            'score_logika' => (float) $this->score_logika,
            'score_sosial' => (float) $this->score_sosial,
            'score_bahasa' => (float) $this->score_bahasa,
            'score_kreativitas' => (float) $this->score_kreativitas,
            'score_analitis' => (float) $this->score_analitis,
            'nilai_mtk' => (float) $this->nilai_mtk,
            'nilai_bindo' => (float) $this->nilai_bindo,
            'nilai_bing' => (float) $this->nilai_bing,
            'nilai_ipa' => (float) $this->nilai_ipa,
            'nilai_ips' => (float) $this->nilai_ips,
        ];
    }

    /**
     * @return BelongsTo<Criteria, $this>
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }

    /**
     * @return BelongsTo<Major, $this>
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class, 'jurusan_id');
    }
}
