<?php

namespace Tests\Feature;

use App\Models\AcademicScore;
use App\Models\Major;
use App\Models\MlModel;
use App\Models\Question;
use App\Models\RecommendationSession;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\CriteriaSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\QuestionSeeder;
use Database\Seeders\SubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RecommendationSubmitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function student_can_complete_recommendation_with_stub_ml(): void
    {
        $this->seed([
            SubjectSeeder::class,
            MajorSeeder::class,
            CriteriaSeeder::class,
            QuestionSeeder::class,
        ]);

        MlModel::factory()->active()->create();

        $user = User::factory()->create(['role' => 'siswa']);
        $student = Student::create([
            'user_id' => $user->id,
            'nama' => 'Budi Tes',
        ]);

        $session = RecommendationSession::create([
            'student_id' => $student->id,
            'status' => 'draft',
        ]);

        $answers = [];
        foreach (Question::with('options')->where('is_active', true)->orderBy('sort_order')->get() as $question) {
            $option = $question->options->firstWhere('score', 4) ?? $question->options->first();
            $this->assertNotNull($option);
            $answers[] = [
                'question_id' => $question->id,
                'question_option_id' => $option->id,
                'answer' => $option->score,
            ];
        }

        $this->actingAs($user)->post(route('user.tes.submit'), [
            'session_id' => $session->id,
            'answers' => $answers,
        ])->assertRedirect(route('user.academic-scores.index'));

        foreach (\App\Models\Subject::query()->where('is_required', true)->get() as $subject) {
            AcademicScore::create([
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'nilai' => 80,
                'is_required' => true,
            ]);
        }

        $this->actingAs($user)->post(route('user.recommendations.process'))
            ->assertRedirect(route('user.hasil', ['sessionId' => $session->id]));

        $session->refresh();
        $this->assertSame('completed', $session->status);
        $expected = min(3, Major::query()->where('is_active', true)->count());
        $this->assertCount($expected, $session->results);
        $this->assertGreaterThanOrEqual(0, (float) $session->results->first()->score);
    }

    #[Test]
    public function submit_fails_when_required_academic_scores_missing(): void
    {
        $this->seed([
            SubjectSeeder::class,
            MajorSeeder::class,
            CriteriaSeeder::class,
            QuestionSeeder::class,
        ]);

        MlModel::factory()->active()->create();

        $user = User::factory()->create(['role' => 'siswa']);
        $student = Student::create([
            'user_id' => $user->id,
            'nama' => 'Ani Tes',
        ]);

        $session = RecommendationSession::create([
            'student_id' => $student->id,
            'status' => 'draft',
        ]);

        $answers = [];
        foreach (Question::with('options')->where('is_active', true)->orderBy('sort_order')->get() as $question) {
            $option = $question->options->firstWhere('score', 4) ?? $question->options->first();
            $answers[] = [
                'question_id' => $question->id,
                'question_option_id' => $option->id,
                'answer' => $option->score,
            ];
        }

        $this->actingAs($user)->post(route('user.tes.submit'), [
            'session_id' => $session->id,
            'answers' => $answers,
        ])->assertRedirect(route('user.academic-scores.index'));

        $this->actingAs($user)->post(route('user.recommendations.process'))
            ->assertRedirect(route('user.recommendations.index'))
            ->assertSessionHasErrors('nilai_akademik');

        $this->assertSame('draft', $session->fresh()->status);
    }
}
