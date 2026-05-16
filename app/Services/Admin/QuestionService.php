<?php

namespace App\Services\Admin;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    public function createQuestion(array $data): Question
    {
        return DB::transaction(function () use ($data) {
            $question = Question::create([
                'criteria_id' => $data['criteria_id'],
                'pertanyaan'  => $data['pertanyaan'],
                'is_active'   => (bool) ($data['is_active'] ?? true),
                'sort_order'  => (int) ($data['sort_order'] ?? 0),
            ]);

            foreach ($data['options'] as $index => $option) {
                $question->options()->create([
                    'opsi'       => $option['opsi'],
                    'score'      => (int) $option['score'],
                    'sort_order' => isset($option['sort_order']) ? (int) $option['sort_order'] : $index,
                ]);
            }

            return $question;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateQuestion(Question $question, array $data): void
    {
        DB::transaction(function () use ($question, $data) {
            $question->update([
                'criteria_id' => $data['criteria_id'],
                'pertanyaan'  => $data['pertanyaan'],
                'is_active'   => (bool) ($data['is_active'] ?? false),
                'sort_order'  => (int) ($data['sort_order'] ?? 0),
            ]);

            if (! empty($data['options']) && $question->studentAnswers()->doesntExist()) {
                $question->options()->delete();
                foreach ($data['options'] as $index => $option) {
                    $question->options()->create([
                        'opsi'       => $option['opsi'],
                        'score'      => (int) $option['score'],
                        'sort_order' => isset($option['sort_order']) ? (int) $option['sort_order'] : $index,
                    ]);
                }
            }
        });
    }

    public function deleteQuestion(Question $question): ?string
    {
        if ($question->studentAnswers()->exists()) {
            return 'Pertanyaan tidak dapat dihapus karena sudah dijawab siswa.';
        }

        DB::transaction(function () use ($question) {
            $question->options()->delete();
            $question->delete();
        });

        return null;
    }
}
