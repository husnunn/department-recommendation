<?php

namespace Tests\Feature;

use App\Models\AcademicScore;
use App\Models\MlModel;
use App\Models\Question;
use App\Models\RecommendationSession;
use App\Models\Student;
use App\Models\User;
use App\Services\MachineLearning\FastApiClient;
use App\Services\MachineLearning\FastApiPredictionResponse;
use Database\Seeders\CriteriaSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\QuestionSeeder;
use Database\Seeders\SubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RecommendationSessionFailureMessageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function failed_recommendation_session_persists_prediction_error_message(): void
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
            'nama' => 'Siswa Gagal Prediksi',
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

        $this->mock(FastApiClient::class, function ($mock): void {
            $mock->shouldReceive('predict')
                ->once()
                ->andReturn(FastApiPredictionResponse::fail('Layanan ML tidak tersedia.'));
        });

        Config::set('ml.stub', false);
        Config::set('ml.base_url', 'http://127.0.0.1:9');

        $this->actingAs($user)->post(route('user.recommendations.process'))
            ->assertRedirect(route('user.recommendations.index'))
            ->assertSessionHasErrors('rekomendasi');

        $session->refresh();
        $this->assertSame('failed', $session->status);
        $this->assertSame('Layanan ML tidak tersedia.', $session->failure_message);
    }
}
