<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuestionRequest;
use App\Http\Requests\Admin\UpdateQuestionRequest;
use App\Models\Criteria;
use App\Models\Question;
use App\Services\Admin\QuestionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $questions = Question::query()
            ->with(['criteria:id,nama_kriteria'])
            ->withCount('options')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('pertanyaan', 'like', '%'.$q.'%')
                        ->orWhereHas('criteria', function ($cq) use ($q) {
                            $cq->where('nama_kriteria', 'like', '%'.$q.'%');
                        });
                });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.questions.index', [
            'questions' => $questions,
            'q'         => $q,
        ]);
    }

    public function create(): View
    {
        $criteriaList = Criteria::query()->orderBy('nama_kriteria')->get(['id', 'nama_kriteria']);

        return view('admin.questions.create', compact('criteriaList'));
    }

    public function store(StoreQuestionRequest $request, QuestionService $questionService): RedirectResponse
    {
        $questionService->createQuestion($request->validated());

        return redirect()
            ->route('admin.pertanyaan.index')
            ->with('success', 'Pertanyaan dan opsi jawaban berhasil disimpan.');
    }

    public function show(Question $question): View
    {
        $question->load(['criteria', 'options' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question): View
    {
        $question->load(['options' => fn ($q) => $q->orderBy('sort_order')]);
        $criteriaList = Criteria::query()->orderBy('nama_kriteria')->get(['id', 'nama_kriteria']);
        $canEditOptions = $question->studentAnswers()->doesntExist();

        return view('admin.questions.edit', compact('question', 'criteriaList', 'canEditOptions'));
    }

    public function update(UpdateQuestionRequest $request, Question $question, QuestionService $questionService): RedirectResponse
    {
        $questionService->updateQuestion($question, $request->validated());

        return redirect()
            ->route('admin.pertanyaan.index')
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Question $question, QuestionService $questionService): RedirectResponse
    {
        $error = $questionService->deleteQuestion($question);

        if ($error !== null) {
            return redirect()
                ->route('admin.pertanyaan.index')
                ->with('error', $error);
        }

        return redirect()
            ->route('admin.pertanyaan.index')
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
