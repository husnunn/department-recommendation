<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RecommendationSession;
use App\Services\Report\RecommendationPdfService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RecommendationDownloadController extends Controller
{
    public function __construct(
        private readonly RecommendationPdfService $pdfService,
    ) {}

    public function __invoke(int $session): Response
    {
        $student = Auth::user()->student;
        if ($student === null) {
            abort(403);
        }

        $recommendationSession = RecommendationSession::query()->findOrFail($session);

        return $this->pdfService->download($student, $recommendationSession);
    }
}
