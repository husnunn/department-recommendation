<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            SubjectSeeder::class,
            MajorSeeder::class,
            CriteriaSeeder::class,
            QuestionSeeder::class,
            TrainingDatasetSeeder::class,
        ]);
    }
}
