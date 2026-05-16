<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\RecommendationSession;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\CriteriaSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuestionnaireAnswerIntegrityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function submit_rejects_mismatched_option_score(): void
    {
        $this->seed([
            CriteriaSeeder::class,
            MajorSeeder::class,
            QuestionSeeder::class,
        ]);

        $user = User::factory()->create(['role' => 'siswa']);
        $student = Student::create([
            'user_id' => $user->id,
            'nama' => 'Tes',
        ]);

        $session = RecommendationSession::create([
            'student_id' => $student->id,
            'status' => 'draft',
        ]);

        $question = Question::with('options')->where('is_active', true)->first();
        $this->assertNotNull($question);
        $option = $question->options->first();
        $this->assertNotNull($option);

        $wrongAnswer = $option->score === 5 ? 1 : ($option->score + 1);

        $this->actingAs($user)->post(route('user.tes.submit'), [
            'session_id' => $session->id,
            'answers' => [[
                'question_id' => $question->id,
                'question_option_id' => $option->id,
                'answer' => $wrongAnswer,
            ]],
        ])->assertSessionHasErrors('answers.0');
    }
}
