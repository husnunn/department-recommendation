<?php

namespace App\Services\Recommendation;

use App\Models\Major;
use App\Models\RecommendationSession;

class RecommendationResultService
{
    /**
     * Menyimpan Top-N hasil prediksi (dipanggil dalam transaksi DB di luar kelas ini).
     *
     * @param  list<array{major_id: int, rank: int, score: float}>  $rows
     */
    public function replaceResults(RecommendationSession $session, array $rows): void
    {
        $session->results()->delete();

        foreach ($rows as $row) {
            $session->results()->create([
                'major_id' => $row['major_id'],
                'rank' => $row['rank'],
                'score' => round($row['score'], 2),
            ]);
        }
    }

    /**
     * Prediksi stub: mengambil hingga 3 jurusan aktif untuk lingkungan tanpa FastAPI.
     *
     * @return list<array{major_id: int, rank: int, score: float}>
     */
    public function stubTopMajors(): array
    {
        $majorIds = Major::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->limit(3)
            ->pluck('id')
            ->all();

        $scores = [88.0, 76.0, 68.0];
        $out = [];
        $rank = 1;
        foreach ($majorIds as $idx => $majorId) {
            $out[] = [
                'major_id' => (int) $majorId,
                'rank' => $rank,
                'score' => $scores[$idx] ?? (62.0 - $idx),
            ];
            $rank++;
        }

        return $out;
    }
}
