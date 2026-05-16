<?php

namespace App\Services\Report;

use App\Models\RecommendationSession;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RecommendationPdfService
{
    public function download(Student $student, RecommendationSession $session): Response
    {
        if ((int) $session->student_id !== (int) $student->id) {
            abort(403);
        }

        if ($session->status !== 'completed') {
            abort(404, 'PDF hanya tersedia untuk sesi yang selesai.');
        }

        $session->load([
            'results' => fn ($q) => $q->with('major:id,nama_jurusan,deskripsi')->orderBy('rank'),
        ]);

        $pdf = Pdf::loadView('pdf.recommendation-result', [
            'student' => $student,
            'session' => $session,
            'results' => $session->results,
        ]);

        $slug = Str::slug($student->nama ?: 'siswa');
        $date = $session->finished_at?->format('Y-m-d') ?? now()->format('Y-m-d');
        $filename = "rekomendasi-jurusan-{$slug}-{$date}.pdf";

        return $pdf->download($filename);
    }
}
