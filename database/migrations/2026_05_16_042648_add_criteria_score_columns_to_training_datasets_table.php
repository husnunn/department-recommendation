<?php

use App\Models\Criteria;
use App\Models\TrainingDataset;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_datasets', function (Blueprint $table) {
            $table->decimal('score_logika', 8, 4)->nullable()->after('criteria_id');
            $table->decimal('score_sosial', 8, 4)->nullable()->after('score_logika');
            $table->decimal('score_bahasa', 8, 4)->nullable()->after('score_sosial');
            $table->decimal('score_kreativitas', 8, 4)->nullable()->after('score_bahasa');
            $table->decimal('score_analitis', 8, 4)->nullable()->after('score_kreativitas');
        });

        foreach (TrainingDataset::query()->cursor() as $row) {
            $nama = Criteria::query()->whereKey($row->criteria_id)->value('nama_kriteria');
            $scores = TrainingDataset::syntheticScoresFromDominantNama(is_string($nama) ? $nama : '');
            $row->forceFill($scores)->save();
        }
    }

    public function down(): void
    {
        Schema::table('training_datasets', function (Blueprint $table) {
            $table->dropColumn([
                'score_logika',
                'score_sosial',
                'score_bahasa',
                'score_kreativitas',
                'score_analitis',
            ]);
        });
    }
};
