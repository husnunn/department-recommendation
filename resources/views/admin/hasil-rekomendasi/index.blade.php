@extends('admin.layouts.app')
@section('title', 'Hasil rekomendasi')
@section('page-title', 'Hasil rekomendasi')

@section('content')
    <x-admin.page-header
        title="Hasil rekomendasi"
        description="Sesi rekomendasi per siswa beserta ranking jurusan."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Hasil rekomendasi', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.hasil-rekomendasi') }}" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Nama, NISN, atau jurusan…"
                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-2 pl-10 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary sm:w-72"
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
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Siswa</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">NISN</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Status</th>
                        <th class="min-w-[10rem] px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Keterangan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Top jurusan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Mulai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($sessions as $session)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="px-5 py-3 font-medium text-on-surface">{{ $session->student?->nama ?? '—' }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $session->student?->nisn ?? '—' }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">
                                @if ($session->status === 'completed')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Selesai</span>
                                @elseif ($session->status === 'failed')
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-800">Gagal</span>
                                @elseif ($session->status === 'processing')
                                    <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-900">Diproses</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">Draft</span>
                                @endif
                            </td>
                            <td class="max-w-xs px-5 py-3 align-top text-xs leading-relaxed text-on-surface-variant">
                                @if ($session->status === 'failed' && filled($session->failure_message))
                                    <span class="whitespace-pre-wrap break-words text-error">{{ $session->failure_message }}</span>
                                @else
                                    <span class="text-outline-variant">—</span>
                                @endif
                            </td>
                            <td class="max-w-xs px-5 py-3 text-on-surface">
                                @php
                                    $tops = $session->results->take(3)->map(fn ($r) => $r->major?->nama_jurusan)->filter();
                                @endphp
                                {{ $tops->isNotEmpty() ? $tops->implode(', ') : '—' }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-3 text-on-surface-variant">{{ $session->started_at?->format('d M Y H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">rule</span>
                                Belum ada sesi rekomendasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($sessions->hasPages())
            <div class="border-t border-outline-variant/30 px-5 py-4">{{ $sessions->links() }}</div>
        @endif
    </div>
@endsection
