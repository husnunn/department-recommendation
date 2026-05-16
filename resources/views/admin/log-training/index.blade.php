@extends('admin.layouts.app')
@section('title', 'Log training')
@section('page-title', 'Log training')

@section('content')
    <x-admin.page-header
        title="Log training"
        description="Riwayat proses training dan metrik evaluasi per model."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Log training', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.log-training') }}" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Nama / versi model…"
                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-2 pl-10 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary sm:w-64"
                    />
                </div>
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Cari</button>
            </form>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Waktu mulai</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Model</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline min-w-[12rem]">Keterangan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Akurasi</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">F1</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="whitespace-nowrap px-5 py-3 text-on-surface-variant">{{ $log->started_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td class="px-5 py-3 text-on-surface">
                                {{ $log->mlModel?->model_name ?? '—' }}
                                <span class="text-on-surface-variant">v{{ $log->mlModel?->version }}</span>
                            </td>
                            <td class="px-5 py-3">
                                @if ($log->status === 'success')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Selesai</span>
                                @elseif ($log->status === 'failed')
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">Gagal</span>
                                @else
                                    <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-900">Berjalan</span>
                                @endif
                            </td>
                            <td class="max-w-md px-5 py-3 align-top text-xs leading-relaxed text-on-surface-variant">
                                @if (filled($log->message))
                                    <span class="whitespace-pre-wrap break-words text-on-surface" title="{{ $log->message }}">{{ $log->message }}</span>
                                @else
                                    <span class="text-outline-variant">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $log->accuracy !== null ? number_format((float) $log->accuracy, 2) : '—' }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $log->f1_score !== null ? number_format((float) $log->f1_score, 2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">history_edu</span>
                                Belum ada log training.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="border-t border-outline-variant/30 px-5 py-4">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
