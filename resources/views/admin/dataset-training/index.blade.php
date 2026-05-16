@extends('admin.layouts.app')
@section('title', 'Dataset training')
@section('page-title', 'Dataset training')

@section('content')
    <x-admin.page-header
        title="Dataset training"
        description="Baris dataset dengan kriteria, nilai mapel, dan label jurusan."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Dataset training', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.dataset-training') }}" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari kriteria / jurusan…"
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
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Kriteria (ref.)</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Skor kriteria</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Jurusan</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">MTK</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">BIN</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">BIG</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">IPA</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-outline">IPS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($datasets as $row)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="whitespace-nowrap px-4 py-3 text-on-surface">{{ $row->criteria?->nama_kriteria ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-on-surface-variant">
                                L{{ number_format((float) $row->score_logika, 0) }}
                                · S{{ number_format((float) $row->score_sosial, 0) }}
                                · B{{ number_format((float) $row->score_bahasa, 0) }}
                                · K{{ number_format((float) $row->score_kreativitas, 0) }}
                                · A{{ number_format((float) $row->score_analitis, 0) }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-on-surface">{{ $row->major?->nama_jurusan ?? '—' }}</td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $row->nilai_mtk }}</td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $row->nilai_bindo }}</td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $row->nilai_bing }}</td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $row->nilai_ipa }}</td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $row->nilai_ips }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">database</span>
                                Belum ada baris dataset.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($datasets->hasPages())
            <div class="border-t border-outline-variant/30 px-5 py-4">{{ $datasets->links() }}</div>
        @endif
    </div>
@endsection
