<?php

namespace App\Http\Requests\User;

use App\Models\QuestionOption;
use App\Models\RecommendationSession;
use App\Services\Recommendation\RecommendationPayloadBuilder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionnaireAnswersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSiswa() ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'session_id' => ['required', 'exists:recommendation_sessions,id'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'exists:questions,id'],
            'answers.*.question_option_id' => ['required', 'exists:question_options,id'],
            'answers.*.answer' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $user = $this->user();
            $student = $user?->student;
            if ($student === null) {
                $validator->errors()->add('session_id', 'Profil siswa tidak ditemukan.');

                return;
            }

            if (! app(RecommendationPayloadBuilder::class)->profileNameIsComplete($student)) {
                $validator->errors()->add('profil', 'Lengkapi nama di halaman profil terlebih dahulu.');

                return;
            }

            $session = RecommendationSession::query()->find($this->input('session_id'));
            if ($session === null || (int) $session->student_id !== (int) $student->id) {
                $validator->errors()->add('session_id', 'Sesi tidak valid.');

                return;
            }

            foreach ($this->input('answers', []) as $idx => $answer) {
                $questionId = (int) ($answer['question_id'] ?? 0);
                $optionId = (int) ($answer['question_option_id'] ?? 0);
                $ans = (int) ($answer['answer'] ?? 0);

                $option = QuestionOption::query()
                    ->whereKey($optionId)
                    ->where('question_id', $questionId)
                    ->first();

                if ($option === null) {
                    $validator->errors()->add('answers.'.$idx, 'Opsi jawaban tidak valid untuk pertanyaan ini.');

                    continue;
                }

                if ((int) $option->score !== $ans) {
                    $validator->errors()->add('answers.'.$idx, 'Skor jawaban harus sama dengan opsi yang dipilih.');
                }
            }
        });
    }
}
