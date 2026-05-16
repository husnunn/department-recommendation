<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\MlModelsController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\RecommendationReportController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TrainingDatasetController;
use App\Http\Controllers\Admin\TrainingLogController;
use App\Http\Controllers\Admin\TrainingModelController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\AcademicScoreController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\HasilController;
use App\Http\Controllers\User\ProfilController;
use App\Http\Controllers\User\RecommendationController;
use App\Http\Controllers\User\RecommendationDownloadController;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\TesController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes — Siswa Auth (Inertia)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Admin Auth Routes (Blade) — Selalu accessible
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Semua user yang login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirect root ke dashboard sesuai role
    Route::get('/', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        }

        return redirect('/user/dashboard');
    })->name('home');
});

/*
|--------------------------------------------------------------------------
| User/Siswa Routes (Inertia + Vue)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class . ':siswa'])
    ->prefix('user')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

        Route::get('/profil', [ProfilController::class, 'index'])->name('user.profil');
        Route::put('/profil', [ProfilController::class, 'update'])->name('user.profil.update');

        Route::get('/academic-scores', [AcademicScoreController::class, 'index'])->name('user.academic-scores.index');
        Route::post('/academic-scores', [AcademicScoreController::class, 'store'])->name('user.academic-scores.store');

        Route::get('/tes', [TesController::class, 'index'])->name('user.tes');
        Route::post('/tes/submit', [TesController::class, 'submit'])->name('user.tes.submit');

        Route::get('/recommendations', [RecommendationController::class, 'index'])->name('user.recommendations.index');
        Route::post('/recommendations/process', [RecommendationController::class, 'process'])->name('user.recommendations.process');
        Route::get('/recommendations/{session}/download', RecommendationDownloadController::class)->name('user.recommendations.download');

        Route::get('/riwayat', [RiwayatController::class, 'index'])->name('user.riwayat');

        Route::get('/hasil/{sessionId}', [HasilController::class, 'show'])->name('user.hasil');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (Blade) — Only RoleMiddleware (handles auth + role check)
| RoleMiddleware redirects unauthenticated users to /admin/login
|--------------------------------------------------------------------------
*/
Route::middleware([RoleMiddleware::class . ':admin,superadmin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::name('admin.')->group(function () {
            Route::resource('jurusan', MajorController::class)->parameters(['jurusan' => 'major']);
            Route::resource('kriteria', CriteriaController::class)->parameters(['kriteria' => 'criteria']);
            Route::resource('pertanyaan', QuestionController::class)->parameters(['pertanyaan' => 'question']);
            Route::resource('mapel', SubjectController::class)->parameters(['mapel' => 'subject']);
            Route::resource('sekolah', SchoolController::class)->parameters(['sekolah' => 'school']);
            Route::resource('kelas', SchoolClassController::class)->parameters(['kelas' => 'schoolClass']);
        });

        Route::get('/siswa', [StudentController::class, 'index'])->name('admin.siswa');

        Route::get('/dataset-training', [TrainingDatasetController::class, 'index'])->name('admin.dataset-training');
        Route::get('/training-model', [TrainingModelController::class, 'index'])->name('admin.training-model');
        Route::post('/training-model', [TrainingModelController::class, 'store'])->name('admin.training-model.store');
        Route::get('/ml-models', [MlModelsController::class, 'index'])->name('admin.ml-models');
        Route::post('/ml-models/{mlModel}/activate', [MlModelsController::class, 'activate'])->name('admin.ml-models.activate');
        Route::get('/log-training', [TrainingLogController::class, 'index'])->name('admin.log-training');
        Route::get('/hasil-rekomendasi', [RecommendationReportController::class, 'index'])->name('admin.hasil-rekomendasi');

        Route::get('/laporan', function () {
            return view('admin.laporan.index');
        })->name('admin.laporan');

        Route::get('/manajemen-admin', [AdminUserController::class, 'index'])
            ->middleware([RoleMiddleware::class . ':superadmin'])
            ->name('admin.manajemen-admin');
    });
