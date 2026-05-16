<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    protected $model = SchoolClass::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'nama_kelas' => 'XII '.fake()->randomElement(['IPA 1', 'IPS 2', 'Bahasa']),
            'jurusan' => fake()->randomElement(['IPA', 'IPS', 'Bahasa']),
            'is_active' => true,
        ];
    }
}
