<?php

namespace App\Http\Requests\Admin;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Question $question */
        $question = $this->route('question');

        $rules = [
            'criteria_id' => ['required', 'exists:criteria,id'],
            'pertanyaan'  => ['required', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ];

        if ($question->studentAnswers()->exists()) {
            return $rules;
        }

        return array_merge($rules, [
            'options'              => ['required', 'array', 'min:1'],
            'options.*.opsi'       => ['required', 'string', 'max:255'],
            'options.*.score'      => ['required', 'integer', 'min:1', 'max:5'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ]);
    }
}
