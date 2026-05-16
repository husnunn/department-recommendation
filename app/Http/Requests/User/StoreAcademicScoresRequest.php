<?php

namespace App\Http\Requests\User;

use App\Models\Subject;
use App\Services\Recommendation\RecommendationPayloadBuilder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicScoresRequest extends FormRequest
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
            'scores' => ['required', 'array', 'min:1'],
            'scores.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $student = $this->user()?->student;
            if ($student === null) {
                $validator->errors()->add('scores', 'Profil siswa tidak ditemukan.');

                return;
            }

            $builder = app(RecommendationPayloadBuilder::class);

            if (! $builder->profileNameIsComplete($student)) {
                $validator->errors()->add('scores', 'Lengkapi nama di halaman profil terlebih dahulu.');

                return;
            }

            if (! $builder->questionnaireIsComplete($student)) {
                $validator->errors()->add('scores', 'Selesaikan kuesioner terlebih dahulu sebelum mengisi nilai akademik.');

                return;
            }

            $scores = $this->input('scores', []);
            if (! is_array($scores)) {
                return;
            }

            $requiredSubjectIds = Subject::query()
                ->where('is_active', true)
                ->where('is_required', true)
                ->pluck('id');

            foreach ($requiredSubjectIds as $subjectId) {
                $key = (string) $subjectId;
                $value = $scores[$subjectId] ?? $scores[$key] ?? null;
                if ($value === null || $value === '') {
                    $validator->errors()->add('scores.'.$key, 'Nilai mapel wajib harus diisi.');
                }
            }

            foreach ($scores as $subjectId => $nilai) {
                if ($nilai === null || $nilai === '') {
                    continue;
                }
                if (! Subject::query()->whereKey($subjectId)->exists()) {
                    $validator->errors()->add('scores.'.(string) $subjectId, 'Mata pelajaran tidak valid.');
                }
            }
        });
    }
}
