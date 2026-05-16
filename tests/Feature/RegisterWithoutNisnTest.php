<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterWithoutNisnTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function student_can_register_without_nisn(): void
    {
        $response = $this->post(route('register'), [
            'nama' => 'Siswa Baru',
            'username' => 'siswa_baru',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('user.dashboard'));

        $user = User::query()->where('username', 'siswa_baru')->first();
        $this->assertNotNull($user);
        $this->assertSame('siswa', $user->role);

        $student = Student::query()->where('user_id', $user->id)->first();
        $this->assertNotNull($student);
        $this->assertSame('Siswa Baru', $student->nama);
        $this->assertNull($student->nisn);
    }
}
