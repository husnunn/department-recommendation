<?php

namespace App\Services\Recommendation;

use App\Models\Criteria;
use App\Models\Question;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Collection;

/**
 * Membangun payload fitur untuk FastAPI sesuai RULES_PERHITUNGAN:
 * rata-rata Likert per kriteria, normalisasi 0–100, lima skor kriteria canonikal,
 * lima nilai mapel wajib, dan validasi kelengkapan.
 */
class RecommendationPayloadBuilder
{
    /**
     * Urutan nama kriteria di basis data (CriteriaSeeder) yang dipetakan ke nama fitur.
     *
     * @var list<string>
     */
    public const CANONICAL_CRITERIA_NAMES = [
        'Logika',
        'Sosial',
        'Bahasa',
        'Kreativitas',
        'Analitis',
    ];

    /**
     * Urutan kunci fitur wajib (§8–§9 RULES_PERHITUNGAN).
     *
     * @var list<string>
     */
    public const FEATURE_KEYS_ORDER = [
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
    ];

    /**
     * @return array<string, list<string>>
     */
    public function validationErrors(Student $student): array
    {
        $student->loadMissing([
            'answers.question.criteria',
            'academicScores.subject',
        ]);

        $errors = [];

        if (! $this->profileNameIsComplete($student)) {
            $errors['profil'] = [
                'Nama wajib diisi di halaman profil sebelum melanjutkan.',
            ];
        }

        if (! $this->canonicalCriteriaPresent()) {
            $errors['struktur_kriteria'] = [
                'Struktur kriteria pada server tidak lengkap. Hubungi admin.',
            ];
        }

        if (! $this->questionnaireIsComplete($student)) {
            $errors['kuesioner'] = [
                'Kuesioner belum lengkap.',
            ];
        }

        if (! $this->areMandatoryAcademicScoresPresent($student)) {
            $errors['nilai_akademik'] = [
                'Nilai akademik wajib belum lengkap.',
            ];
        }

        return $errors;
    }

    /**
     * Payload JSON untuk FastAPI (§8).
     *
     * @return array{student_id: int, features: array<string, float>}
     */
    public function buildRequestPayload(Student $student): array
    {
        $student->loadMissing([
            'answers.question.criteria',
            'academicScores.subject',
        ]);

        $features = $this->buildFeatures($student);

        return [
            'student_id' => $student->id,
            'features' => $features,
        ];
    }

    /**
     * Daftar nilai fitur dengan urutan tetap §9 (untuk dokumentasi / logging).
     *
     * @param  array<string, float>  $features
     * @return list<float>
     */
    public function orderedFeatureValues(array $features): array
    {
        $out = [];
        foreach (self::FEATURE_KEYS_ORDER as $key) {
            $out[] = (float) ($features[$key] ?? 0.0);
        }

        return $out;
    }

    /**
     * Langkah 6 ALUR: semua pertanyaan aktif punya jawaban di `student_answers`.
     */
    public function questionnaireIsComplete(Student $student): bool
    {
        $student->loadMissing(['answers']);

        return $this->questionnaireIsCompleteUsingLoadedAnswers($student);
    }

    /**
     * Langkah 5 minimal: nama siswa terisi (identitas untuk rekomendasi).
     */
    public function profileNameIsComplete(Student $student): bool
    {
        return trim((string) $student->nama) !== '';
    }

    private function canonicalCriteriaPresent(): bool
    {
        $count = Criteria::query()
            ->whereIn('nama_kriteria', self::CANONICAL_CRITERIA_NAMES)
            ->count();

        return $count === count(self::CANONICAL_CRITERIA_NAMES);
    }

    private function questionnaireIsCompleteUsingLoadedAnswers(Student $student): bool
    {
        $questionsByCriteria = Question::query()
            ->where('is_active', true)
            ->get(['id', 'criteria_id'])
            ->groupBy('criteria_id');

        if ($questionsByCriteria->isEmpty()) {
            return false;
        }

        $answeredQuestionIds = $student->answers
            ->pluck('question_id')
            ->unique()
            ->flip();

        foreach ($questionsByCriteria as $criteriaId => $rows) {
            foreach ($rows as $question) {
                if (! $answeredQuestionIds->has($question->id)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function areMandatoryAcademicScoresPresent(Student $student): bool
    {
        $requiredIds = Subject::query()
            ->where('is_active', true)
            ->where('is_required', true)
            ->pluck('id');

        if ($requiredIds->isEmpty()) {
            return false;
        }

        foreach ($requiredIds as $subjectId) {
            $row = $student->academicScores->firstWhere('subject_id', $subjectId);
            if ($row === null) {
                return false;
            }
            $nilai = (float) $row->nilai;
            if ($nilai < 0 || $nilai > 100) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string, float>
     */
    private function buildFeatures(Student $student): array
    {
        $criteriaByName = Criteria::query()
            ->whereIn('nama_kriteria', self::CANONICAL_CRITERIA_NAMES)
            ->get()
            ->keyBy('nama_kriteria');

        $sums = [];
        $counts = [];

        foreach ($student->answers as $answer) {
            $question = $answer->question;
            if ($question === null) {
                continue;
            }
            $criteria = $question->criteria;
            if ($criteria === null) {
                continue;
            }
            $name = $criteria->nama_kriteria;
            if (! in_array($name, self::CANONICAL_CRITERIA_NAMES, true)) {
                continue;
            }
            $sums[$name] = ($sums[$name] ?? 0) + (float) $answer->answer;
            $counts[$name] = ($counts[$name] ?? 0) + 1;
        }

        $features = [];

        foreach (self::CANONICAL_CRITERIA_NAMES as $nama) {
            $key = $this->featureKeyForCriteriaName($nama);
            $criteria = $criteriaByName->get($nama);
            $expectedQuestions = $criteria
                ? Question::query()->where('criteria_id', $criteria->id)->where('is_active', true)->count()
                : 0;

            $cnt = (int) ($counts[$nama] ?? 0);
            if ($expectedQuestions < 1 || $cnt !== $expectedQuestions) {
                $avg = 0.0;
            } else {
                $avg = $sums[$nama] / $cnt;
            }

            $features[$key] = $this->normalizeLikertAverageTo100($avg);
        }

        foreach ($this->nilaiMapelStandar($student->academicScores) as $col => $val) {
            $features[$col] = $val ?? 0.0;
        }

        return $features;
    }

    private function featureKeyForCriteriaName(string $namaKriteria): string
    {
        return match ($namaKriteria) {
            'Logika' => 'score_logika',
            'Sosial' => 'score_sosial',
            'Bahasa' => 'score_bahasa',
            'Kreativitas' => 'score_kreativitas',
            'Analitis' => 'score_analitis',
            default => 'score_logika',
        };
    }

    /**
     * Normalisasi skala 1–5 ke 0–100: ((avg - 1) / 4) * 100
     */
    private function normalizeLikertAverageTo100(float $averageLikert): float
    {
        $scaled = (($averageLikert - 1) / 4) * 100;

        return round(max(0, min(100, $scaled)), 4);
    }

    /**
     * @param  Collection<int, \App\Models\AcademicScore>  $scores
     * @return array<string, float|null>
     */
    private function nilaiMapelStandar(Collection $scores): array
    {
        $base = [
            'nilai_mtk' => null,
            'nilai_bindo' => null,
            'nilai_bing' => null,
            'nilai_ipa' => null,
            'nilai_ips' => null,
        ];

        $sums = [];
        $counts = [];

        foreach ($scores as $row) {
            $subject = $row->subject;
            if ($subject === null) {
                continue;
            }
            $column = $this->mapSubjectNameToTrainingColumn($subject->nama_mapel);
            if ($column === null) {
                continue;
            }
            $sums[$column] = ($sums[$column] ?? 0) + (float) $row->nilai;
            $counts[$column] = ($counts[$column] ?? 0) + 1;
        }

        foreach (array_keys($base) as $key) {
            if (isset($counts[$key]) && $counts[$key] > 0) {
                $base[$key] = round($sums[$key] / $counts[$key], 2);
            }
        }

        return $base;
    }

    private function mapSubjectNameToTrainingColumn(string $namaMapel): ?string
    {
        $n = mb_strtolower(trim($namaMapel));

        return match (true) {
            str_contains($n, 'matematika') => 'nilai_mtk',
            str_contains($n, 'bahasa indonesia') => 'nilai_bindo',
            str_contains($n, 'b indonesia') => 'nilai_bindo',
            str_contains($n, 'bahasa inggris') => 'nilai_bing',
            str_contains($n, 'inggris') && ! str_contains($n, 'indonesia') => 'nilai_bing',
            $n === 'ipa' => 'nilai_ipa',
            str_contains($n, 'ilmu pengetahuan alam') => 'nilai_ipa',
            $n === 'ips' => 'nilai_ips',
            str_contains($n, 'ilmu pengetahuan sosial') => 'nilai_ips',
            default => null,
        };
    }
}
