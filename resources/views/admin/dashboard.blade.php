@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <x-admin.page-header
        title="Dashboard"
        description="Ringkasan platform rekomendasi jurusan: siswa, kuesioner, model ML, dan aktivitas terbaru."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a
                href="{{ route('admin.mapel.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-outline-variant bg-surface px-5 py-2.5 text-sm font-medium text-primary shadow-[0_1px_2px_rgba(0,0,0,0.05)] transition-colors hover:bg-surface-container-low"
            >
                <span class="material-symbols-outlined text-[20px]">book</span>
                Mata pelajaran
            </a>
            <a
                href="{{ route('admin.training-model') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary shadow-[0_1px_3px_rgba(0,0,0,0.1)] transition-colors hover:bg-primary-container"
            >
                <span class="material-symbols-outlined text-[20px]">model_training</span>
                Training ulang model
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div
        class="mb-8 flex items-start gap-3 rounded-xl border border-secondary/20 bg-secondary-container/15 p-4 md:p-5"
        role="status"
    >
        <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1">check_circle</span>
        <div>
            <h2 class="text-base font-semibold text-on-secondary-container">Sistem rekomendasi aktif</h2>
            <p class="mt-1 text-sm leading-relaxed text-on-surface-variant">
                Model machine learning yang aktif melayani prediksi jurusan. Pantau metrik di bawah dan log training untuk audit skripsi / operasional.
            </p>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <div
            class="rounded-2xl bg-surface p-5 shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)] xl:col-span-1"
        >
            <div class="mb-4 flex items-start justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-outline">Total siswa</span>
                <span class="material-symbols-outlined text-outline-variant">group</span>
            </div>
            <p class="text-3xl font-semibold tracking-tight text-on-surface">{{ $totalStudents ?? 0 }}</p>
            <p class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                Terdaftar di sistem
            </p>
        </div>

        <div
            class="rounded-2xl bg-surface p-5 shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)] xl:col-span-1"
        >
            <div class="mb-4 flex items-start justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-outline">Jurusan</span>
                <span class="material-symbols-outlined text-outline-variant">school</span>
            </div>
            <p class="text-3xl font-semibold tracking-tight text-on-surface">{{ $totalMajors ?? 0 }}</p>
        </div>

        <div
            class="rounded-2xl bg-surface p-5 shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)] xl:col-span-1"
        >
            <div class="mb-4 flex items-start justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-outline">Pertanyaan</span>
                <span class="material-symbols-outlined text-outline-variant">quiz</span>
            </div>
            <p class="text-3xl font-semibold tracking-tight text-on-surface">{{ $totalQuestions ?? 0 }}</p>
        </div>

        <div
            class="rounded-2xl bg-surface p-5 shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)] xl:col-span-1"
        >
            <div class="mb-4 flex items-start justify-between">
                <span class="text-xs font-medium uppercase tracking-wider text-outline">Rekomendasi</span>
                <span class="material-symbols-outlined text-outline-variant">rule</span>
            </div>
            <p class="text-3xl font-semibold tracking-tight text-on-surface">{{ $totalRecommendations ?? 0 }}</p>
            <p class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                <span class="material-symbols-outlined text-[16px]">history</span>
                Sesi selesai
            </p>
        </div>

        <div
            class="relative overflow-hidden rounded-2xl bg-surface-tint p-5 text-on-primary shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)] xl:col-span-2"
        >
            <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-8 -left-8 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
            <div class="relative z-10">
                <div class="mb-4 flex items-start justify-between">
                    <span class="text-xs font-medium uppercase tracking-wider text-on-primary/80">Model ML</span>
                    <span class="material-symbols-outlined text-on-primary/90">smart_toy</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="mb-1 text-xs font-medium text-on-primary/80">Akurasi</p>
                        <p class="text-2xl font-semibold">{{ $modelAccuracy ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="mb-1 text-xs font-medium text-on-primary/80">Validasi</p>
                        <p class="text-sm font-medium leading-snug text-on-primary/90">{{ $lastValidated ?? 'Belum pernah' }}</p>
                    </div>
                </div>
                <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-on-primary/20">
                    <div
                        class="h-full rounded-full bg-secondary-container transition-all"
                        style="width: {{ min(100, (int) ($modelAccuracyPercent ?? 0)) }}%"
                    ></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div
            class="flex flex-col overflow-hidden rounded-2xl bg-surface shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)]"
        >
            <div class="border-b border-outline-variant/30 p-5">
                <h3 class="text-lg font-semibold text-on-surface">Jurusan paling direkomendasikan</h3>
                <p class="mt-1 text-sm text-on-surface-variant">Top program studi dari prediksi ML (data contoh / placeholder).</p>
            </div>
            <div class="min-h-[200px] flex-1 overflow-x-auto p-5">
                <div class="flex min-w-[320px] items-end justify-between gap-2 md:gap-4">
                @php
                    $sampleMajors = [
                        ['name' => 'Teknik Informatika', 'h' => 220],
                        ['name' => 'Sistem Informasi', 'h' => 180],
                        ['name' => 'Manajemen', 'h' => 150],
                        ['name' => 'Ilmu Komunikasi', 'h' => 110],
                        ['name' => 'Akuntansi', 'h' => 90],
                    ];
                @endphp
                @foreach ($sampleMajors as $major)
                    <div class="group flex w-full flex-col items-center gap-2">
                        <span class="text-xs font-medium text-outline">{{ $major['h'] }}</span>
                        <div
                            class="w-full max-h-[12rem] min-h-[4rem] rounded-t-sm bg-primary/20 transition-colors group-hover:bg-primary"
                            style="height: {{ min($major['h'], 180) }}px"
                        ></div>
                        <span class="w-full truncate text-center text-[11px] text-on-surface-variant" title="{{ $major['name'] }}">
                            {{ $major['name'] }}
                        </span>
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        <div
            class="flex flex-col overflow-hidden rounded-2xl bg-surface shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)]"
        >
            <div class="flex items-center justify-between border-b border-outline-variant/30 p-5">
                <div>
                    <h3 class="text-lg font-semibold text-on-surface">Skor minat rata-rata</h3>
                    <p class="mt-1 text-sm text-on-surface-variant">Berdasarkan kriteria kuesioner (placeholder).</p>
                </div>
            </div>
            <div class="flex flex-1 flex-col justify-center gap-6 p-5">
                @foreach ([['Logika & analisis', 85], ['Kreativitas & seni', 62], ['Komunikasi & sosial', 78], ['Sains & alam', 54]] as [$label, $pct])
                    <div>
                        <div class="mb-2 flex justify-between text-xs font-medium">
                            <span class="text-on-surface-variant">{{ $label }}</span>
                            <span class="font-semibold text-on-surface">{{ $pct }}%</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-surface-container-highest">
                            <div class="h-full rounded-full bg-primary" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div
            class="overflow-hidden rounded-2xl bg-surface shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)]"
        >
            <div class="flex items-center justify-between border-b border-outline-variant/30 p-5">
                <h3 class="text-lg font-semibold text-on-surface">Rekomendasi terbaru</h3>
                <a href="{{ route('admin.laporan') }}" class="text-xs font-semibold text-primary hover:underline">Laporan</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-outline-variant/30 bg-surface-bright">
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Nama siswa</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Jurusan</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-outline">Skor</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-on-surface">
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">rule</span>
                                Belum ada data rekomendasi untuk ditampilkan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="overflow-hidden rounded-2xl bg-surface shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)]"
        >
            <div class="flex items-center justify-between border-b border-outline-variant/30 p-5">
                <h3 class="text-lg font-semibold text-on-surface">Log training terbaru</h3>
                <span class="text-xs font-semibold text-outline">Audit ML</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-outline-variant/30 bg-surface-bright">
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Waktu</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-outline">Akurasi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-on-surface">
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">history_edu</span>
                                Belum ada log training.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
