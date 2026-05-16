@extends('admin.layouts.app')
@section('title', 'Training model')
@section('page-title', 'Training model')

@section('content')
    <x-admin.page-header
        title="Training model"
        description="Ringkasan dataset, model aktif, dan log training terbaru. Tombol retrain akan dihubungkan ke job + FastAPI."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Training model', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a
                href="{{ route('admin.dataset-training') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2 text-sm font-medium text-primary hover:bg-surface-container-low"
            >
                <span class="material-symbols-outlined text-[18px]">database</span>
                Dataset
            </a>
            <a
                href="{{ route('admin.log-training') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary hover:bg-primary-container"
            >
                <span class="material-symbols-outlined text-[18px]">history_edu</span>
                Log training
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-outline-variant/30 bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-outline">Baris dataset</p>
            <p class="mt-2 text-3xl font-bold text-on-surface">{{ $datasetCount }}</p>
        </div>
        <div class="rounded-2xl border border-outline-variant/30 bg-surface p-5 shadow-sm md:col-span-2">
            <p class="text-xs font-semibold uppercase tracking-wider text-outline">Model aktif</p>
            @if ($activeModel)
                <p class="mt-2 text-lg font-semibold text-on-surface">{{ $activeModel->model_name }} <span class="text-on-surface-variant">v{{ $activeModel->version }}</span></p>
                <p class="mt-1 text-sm text-on-surface-variant">{{ $activeModel->algorithm }} · dilatih {{ $activeModel->trained_at?->format('d M Y H:i') ?? '—' }}</p>
            @else
                <p class="mt-2 text-on-surface-variant">Belum ada model yang ditandai aktif.</p>
            @endif
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
            <div class="border-b border-outline-variant/30 px-5 py-4">
                <h2 class="text-lg font-semibold text-on-surface">Versi model (10 terbaru)</h2>
            </div>
            <ul class="divide-y divide-outline-variant/20">
                @forelse ($models as $m)
                    <li class="flex items-center justify-between px-5 py-3 text-sm">
                        <span class="font-medium text-on-surface">{{ $m->model_name }} <span class="text-on-surface-variant">v{{ $m->version }}</span></span>
                        @if ($m->is_active)
                            <span class="rounded-full bg-secondary-container/50 px-2 py-0.5 text-xs font-semibold text-on-secondary-container">Aktif</span>
                        @endif
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-on-surface-variant">Belum ada model tersimpan.</li>
                @endforelse
            </ul>
        </div>
        <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
            <div class="border-b border-outline-variant/30 px-5 py-4">
                <h2 class="text-lg font-semibold text-on-surface">Log terbaru</h2>
            </div>
            <ul class="divide-y divide-outline-variant/20">
                @forelse ($recentLogs as $log)
                    <li class="px-5 py-3 text-sm">
                        <div class="flex justify-between gap-2">
                            <span class="text-on-surface">{{ $log->mlModel?->model_name ?? 'Model #' . $log->model_id }}</span>
                            <span class="shrink-0 text-on-surface-variant">
                                @if ($log->status === 'success')
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Selesai</span>
                                @elseif ($log->status === 'failed')
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">Gagal</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-900">Berjalan</span>
                                @endif
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant">{{ $log->started_at?->format('d M Y H:i') }}</p>
                        @if ($log->status === 'failed' && filled($log->message))
                            <p class="mt-1 text-xs leading-relaxed text-error">{{ $log->message }}</p>
                        @endif
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-on-surface-variant">Belum ada log.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="rounded-2xl border border-dashed border-outline-variant/50 bg-surface-container-low/30 p-6">
        <p class="mb-4 text-center text-sm text-on-surface-variant">
            Training memakai dataset pada halaman Dataset training. Model dan log ditulis oleh job antrian (QUEUE_CONNECTION).
        </p>
        <form method="post" action="{{ route('admin.training-model.store') }}" class="flex justify-center">
            @csrf
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary hover:bg-primary-container"
            >
                <span class="material-symbols-outlined text-[18px]">model_training</span>
                Training ulang model
            </button>
        </form>
    </div>
@endsection
