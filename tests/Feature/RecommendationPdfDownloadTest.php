<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\RecommendationResult;
use App\Models\RecommendationSession;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\MajorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RecommendationPdfDownloadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function student_can_download_pdf_for_own_completed_session(): void
    {
        $this->seed(MajorSeeder::class);

        $user = User::factory()->create(['role' => 'siswa']);
        $student = Student::create([
            'user_id' => $user->id,
            'nama' => 'Budi PDF',
        ]);

        $major = Major::query()->where('is_active', true)->first();
        $this->assertNotNull($major);

        $session = RecommendationSession::create([
            'student_id' => $student->id,
            'status' => 'completed',
            'finished_at' => now(),
        ]);

        RecommendationResult::create([
            'session_id' => $session->id,
            'major_id' => $major->id,
            'rank' => 1,
            'score' => 85.5,
        ]);

        $response = $this->actingAs($user)
            ->get(route('user.recommendations.download', ['session' => $session->id]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    #[Test]
    public function student_cannot_download_pdf_for_another_students_session(): void
    {
        $this->seed(MajorSeeder::class);

        $owner = User::factory()->create(['role' => 'siswa']);
        $ownerStudent = Student::create([
            'user_id' => $owner->id,
            'nama' => 'Pemilik Sesi',
        ]);

        $intruder = User::factory()->create(['role' => 'siswa']);
        Student::create([
            'user_id' => $intruder->id,
            'nama' => 'Siswa Lain',
        ]);

        $major = Major::query()->where('is_active', true)->first();
        $this->assertNotNull($major);

        $session = RecommendationSession::create([
            'student_id' => $ownerStudent->id,
            'status' => 'completed',
            'finished_at' => now(),
        ]);

        RecommendationResult::create([
            'session_id' => $session->id,
            'major_id' => $major->id,
            'rank' => 1,
            'score' => 70,
        ]);

        $this->actingAs($intruder)
            ->get(route('user.recommendations.download', ['session' => $session->id]))
            ->assertForbidden();
    }

    #[Test]
    public function pdf_download_returns_not_found_for_non_completed_session(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $student = Student::create([
            'user_id' => $user->id,
            'nama' => 'Budi Draft',
        ]);

        $session = RecommendationSession::create([
            'student_id' => $student->id,
            'status' => 'draft',
        ]);

        $this->actingAs($user)
            ->get(route('user.recommendations.download', ['session' => $session->id]))
            ->assertNotFound();
    }
}
