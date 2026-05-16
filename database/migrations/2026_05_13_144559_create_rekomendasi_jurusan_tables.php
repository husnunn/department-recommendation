<?php
// File: database/migrations/2026_05_13_000001_create_rekomendasi_jurusan_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Jika Laravel kamu sudah punya migration default create_users_table,
         * jangan buat tabel users dua kali.
         *
         * Pilihan aman:
         * - hapus migration default users jika proyek masih baru, atau
         * - pindahkan struktur users ini ke migration users bawaan Laravel.
         */
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('username')->unique();
        //     $table->string('email')->nullable()->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->enum('role', ['superadmin', 'admin', 'siswa'])
        //         ->default('siswa')
        //         ->index();
        //     $table->rememberToken();
        //     $table->timestamps();
        // });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nisn', 30)->nullable()->unique();
            $table->string('nama');
            $table->string('kelas', 100)->nullable();
            $table->string('asal_sekolah')->nullable();

            $table->timestamps();

            $table->index(['kelas', 'asal_sekolah']);
        });

        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jurusan')->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria')->unique();
            $table->text('deskripsi')->nullable();

            // Opsional, tapi berguna untuk membedakan minat dan bakat.
            $table->enum('kategori', ['minat', 'bakat', 'lainnya'])
                ->default('lainnya')
                ->index();

            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')
                ->constrained('criteria')
                ->cascadeOnDelete();

            $table->text('pertanyaan');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['criteria_id', 'is_active']);
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->string('opsi');

            // Contoh:
            // 1 = Sangat Tidak Setuju
            // 2 = Tidak Setuju
            // 3 = Netral
            // 4 = Setuju
            // 5 = Sangat Setuju
            $table->unsignedTinyInteger('score');

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['question_id', 'score']);
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mapel')->unique();
            $table->boolean('is_required')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->foreignId('question_option_id')
                ->nullable()
                ->constrained('question_options')
                ->nullOnDelete();

            // Tetap disimpan agar mudah diproses ke ML tanpa join ke option.
            $table->unsignedTinyInteger('answer');

            $table->timestamps();

            $table->unique(['student_id', 'question_id']);
            $table->index(['student_id', 'answer']);
        });

        Schema::create('academic_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->decimal('nilai', 5, 2);
            $table->boolean('is_required')->default(false)->index();

            $table->timestamps();

            $table->unique(['student_id', 'subject_id']);
        });

        Schema::create('recommendation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->enum('status', ['draft', 'processing', 'completed', 'failed'])
                ->default('draft')
                ->index();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->index(['student_id', 'status']);
        });

        Schema::create('recommendation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')
                ->constrained('recommendation_sessions')
                ->cascadeOnDelete();

            $table->foreignId('major_id')
                ->constrained('majors')
                ->cascadeOnDelete();

            // Untuk Top-3 rekomendasi.
            $table->unsignedTinyInteger('rank')->default(1);

            // Bisa dipakai untuk probabilitas / skor kecocokan.
            $table->decimal('score', 8, 4);

            $table->timestamps();

            $table->unique(['session_id', 'rank']);
            $table->unique(['session_id', 'major_id']);
            $table->index(['major_id', 'score']);
        });

        Schema::create('training_datasets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('criteria_id')
                ->nullable()
                ->constrained('criteria')
                ->nullOnDelete();

            $table->decimal('nilai_mtk', 5, 2)->nullable();
            $table->decimal('nilai_bindo', 5, 2)->nullable();
            $table->decimal('nilai_bing', 5, 2)->nullable();
            $table->decimal('nilai_ipa', 5, 2)->nullable();
            $table->decimal('nilai_ips', 5, 2)->nullable();

            $table->foreignId('jurusan_id')
                ->constrained('majors')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index(['criteria_id', 'jurusan_id']);
        });

        Schema::create('ml_models', function (Blueprint $table) {
            $table->id();

            $table->string('model_name');
            $table->string('algorithm')->nullable();
            $table->string('version');
            $table->string('model_path')->nullable();

            $table->timestamp('trained_at')->nullable();
            $table->boolean('is_active')->default(false)->index();

            $table->timestamps();

            $table->unique(['model_name', 'version']);
        });

        Schema::create('training_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('model_id')
                ->constrained('ml_models')
                ->cascadeOnDelete();

            $table->decimal('accuracy', 8, 4)->nullable();
            $table->decimal('precision', 8, 4)->nullable();
            $table->decimal('recall', 8, 4)->nullable();
            $table->decimal('f1_score', 8, 4)->nullable();

            $table->enum('status', ['running', 'success', 'failed'])
                ->default('success')
                ->index();

            $table->text('message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->index(['model_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_logs');
        Schema::dropIfExists('ml_models');
        Schema::dropIfExists('training_datasets');
        Schema::dropIfExists('recommendation_results');
        Schema::dropIfExists('recommendation_sessions');
        Schema::dropIfExists('academic_scores');
        Schema::dropIfExists('student_answers');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('criteria');
        Schema::dropIfExists('majors');
        Schema::dropIfExists('students');
        // Schema::dropIfExists('users');
    }
};