<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminSchoolCrudTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_create_school_and_class(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.sekolah.store'), [
                'nama_sekolah' => 'MAN 3 Jombang',
                'lokasi' => 'Jombang',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.sekolah.index'));

        $school = School::query()->where('nama_sekolah', 'MAN 3 Jombang')->first();
        $this->assertNotNull($school);

        $this->actingAs($admin)
            ->post(route('admin.kelas.store'), [
                'school_id' => $school->id,
                'nama_kelas' => 'XII IPA 1',
                'jurusan' => 'IPA',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.kelas.index'));

        $this->assertDatabaseHas('school_classes', [
            'school_id' => $school->id,
            'nama_kelas' => 'XII IPA 1',
        ]);
    }

    #[Test]
    public function questionnaire_page_shows_suggested_profile_banner_when_incomplete(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $user->student()->create([
            'nama' => 'Siswa Banner',
            'nisn' => null,
            'kelas' => null,
            'asal_sekolah' => null,
        ]);

        $this->actingAs($user)
            ->get(route('user.tes'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('User/Tes')
                ->where('profileNameComplete', true)
                ->where('profileSuggestedComplete', false)
            );
    }
}
