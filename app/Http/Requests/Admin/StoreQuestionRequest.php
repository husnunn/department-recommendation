<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
        return [
            'criteria_id' => ['required', 'exists:criteria,id'],
            'pertanyaan'  => ['required', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'options'     => ['required', 'array', 'min:1'],
            'options.*.opsi' => ['required', 'string', 'max:255'],
            'options.*.score' => ['required', 'integer', 'min:1', 'max:5'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ];
    }
}
