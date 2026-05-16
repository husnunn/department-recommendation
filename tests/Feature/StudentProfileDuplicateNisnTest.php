<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentProfileDuplicateNisnTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function profile_update_returns_friendly_error_when_nisn_already_used(): void
    {
        $existing = User::factory()->create(['role' => 'siswa']);
        Student::create([
            'user_id' => $existing->id,
            'nama' => 'Siswa Lama',
            'nisn' => '2202041122',
        ]);

        $user = User::factory()->create(['role' => 'siswa']);
        $student = Student::create([
            'user_id' => $user->id,
            'nama' => 'Siswa Baru',
            'nisn' => null,
        ]);

        $response = $this->actingAs($user)->put(route('user.profil.update'), [
            'nama' => $student->nama,
            'nisn' => '2202041122',
            'kelas' => 'XI IPA 1',
            'asal_sekolah' => 'MAN 3 JOMBANG',
        ]);

        $response
            ->assertSessionHasErrors('nisn')
            ->assertSessionDoesntHaveErrors('nama');

        $message = session('errors')->get('nisn')[0];
        $this->assertStringContainsString('NISN', $message);
        $this->assertStringNotContainsString('SQLSTATE', $message);
        $this->assertNull($student->fresh()->nisn);
    }
}
