<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentOnboardingDashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function dashboard_exposes_onboarding_status_consistent_with_profile_completeness(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        Student::create([
            'user_id' => $user->id,
            'nama' => 'Siswa Dashboard',
        ]);

        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('User/Dashboard')
                ->has('onboarding')
                ->where('onboarding.profileNameComplete', true)
                ->where('onboarding.questionnaireComplete', false)
                ->where('onboarding.academicScoresComplete', false)
                ->where('onboarding.canProcessRecommendation', false)
                ->where('onboarding.recommendedNextRoute', 'user.tes')
            );
    }

    #[Test]
    public function dashboard_directs_incomplete_profile_to_profil_route(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        Student::create([
            'user_id' => $user->id,
            'nama' => '',
        ]);

        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('onboarding.profileNameComplete', false)
                ->where('onboarding.recommendedNextRoute', 'user.profil')
            );
    }
}
