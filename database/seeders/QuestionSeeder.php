<?php
// File: database/seeders/QuestionSeeder.php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $questions = [
                'Logika' => [
                    'Saya senang memecahkan soal yang membutuhkan penalaran.',
                    'Saya mudah memahami pola atau hubungan antar konsep.',
                    'Saya tertarik mencari solusi ketika menghadapi masalah yang rumit.',
                    'Saya suka aktivitas yang membutuhkan perhitungan atau strategi.',
                ],
                'Sosial' => [
                    'Saya senang membantu teman ketika mereka mengalami kesulitan.',
                    'Saya mudah beradaptasi saat bekerja dalam kelompok.',
                    'Saya tertarik dengan kegiatan yang melibatkan komunikasi dengan banyak orang.',
                    'Saya merasa nyaman menjadi pendengar bagi orang lain.',
                ],
                'Bahasa' => [
                    'Saya senang membaca artikel, buku, atau materi berbahasa.',
                    'Saya mudah memahami isi bacaan dan menyimpulkan informasi.',
                    'Saya tertarik mempelajari bahasa asing.',
                    'Saya merasa percaya diri saat menyampaikan pendapat secara lisan atau tulisan.',
                ],
                'Kreativitas' => [
                    'Saya senang membuat desain, gambar, video, atau karya visual.',
                    'Saya sering memiliki ide baru untuk menyelesaikan tugas.',
                    'Saya tertarik pada kegiatan seni, desain, atau konten kreatif.',
                    'Saya suka mencoba cara berbeda dalam menyelesaikan pekerjaan.',
                ],
                'Analitis' => [
                    'Saya senang membandingkan data sebelum mengambil keputusan.',
                    'Saya teliti dalam melihat detail informasi.',
                    'Saya tertarik mencari penyebab dari suatu masalah.',
                    'Saya suka menyusun kesimpulan berdasarkan fakta atau data.',
                ],
            ];

            $options = [
                [
                    'opsi' => 'Sangat Tidak Setuju',
                    'score' => 1,
                    'sort_order' => 1,
                ],
                [
                    'opsi' => 'Tidak Setuju',
                    'score' => 2,
                    'sort_order' => 2,
                ],
                [
                    'opsi' => 'Netral',
                    'score' => 3,
                    'sort_order' => 3,
                ],
                [
                    'opsi' => 'Setuju',
                    'score' => 4,
                    'sort_order' => 4,
                ],
                [
                    'opsi' => 'Sangat Setuju',
                    'score' => 5,
                    'sort_order' => 5,
                ],
            ];

            foreach ($questions as $criteriaName => $items) {
                $criteria = Criteria::where('nama_kriteria', $criteriaName)->first();

                if (! $criteria) {
                    continue;
                }

                foreach ($items as $index => $questionText) {
                    $question = Question::updateOrCreate(
                        [
                            'criteria_id' => $criteria->id,
                            'pertanyaan' => $questionText,
                        ],
                        [
                            'criteria_id' => $criteria->id,
                            'pertanyaan' => $questionText,
                            'is_active' => true,
                            'sort_order' => $index + 1,
                        ]
                    );

                    foreach ($options as $option) {
                        QuestionOption::updateOrCreate(
                            [
                                'question_id' => $question->id,
                                'score' => $option['score'],
                            ],
                            [
                                'question_id' => $question->id,
                                'opsi' => $option['opsi'],
                                'score' => $option['score'],
                                'sort_order' => $option['sort_order'],
                            ]
                        );
                    }
                }
            }
        });
    }
}