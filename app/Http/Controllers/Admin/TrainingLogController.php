<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingLogController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim()->toString();

        $logs = TrainingLog::query()
            ->with(['mlModel:id,model_name,version'])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('mlModel', function ($mq) use ($q) {
                    $mq->where('model_name', 'like', '%'.$q.'%')
                        ->orWhere('version', 'like', '%'.$q.'%');
                });
            })
            ->latest('started_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.log-training.index', [
            'logs' => $logs,
            'q'    => $q,
        ]);
    }
}
