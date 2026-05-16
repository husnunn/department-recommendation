@extends('admin.layouts.app')
@section('title', 'Pertanyaan')
@section('page-title', 'Pertanyaan kuesioner')

@section('content')
    <x-admin.page-header
        title="Pertanyaan kuesioner"
        description="Kelola pertanyaan per kriteria dan opsi jawaban (skor Likert)."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Pertanyaan', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.pertanyaan.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
                    <input type="search" name="q" value="{{ $q }}" class="w-full rounded-lg border py-2 pl-10 pr-3 text-sm sm:w-72" placeholder="Cari…" />
                </div>
                <button type="submit" class="rounded-lg bg-surface px-4 py-2 text-sm ring-1 ring-outline-variant">Cari</button>
            </form>
            <a href="{{ route('admin.pertanyaan.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah pertanyaan
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b bg-surface-container-low">
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Kriteria</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Pertanyaan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Opsi</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-outline">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($questions as $question)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="whitespace-nowrap px-5 py-3 text-on-surface-variant">{{ $question->criteria?->nama_kriteria }}</td>
                            <td class="max-w-md px-5 py-3">{{ Str::limit($question->pertanyaan, 100) }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $question->options_count }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-right">
                                <a href="{{ route('admin.pertanyaan.show', $question) }}" class="mr-2 text-primary hover:underline">Detail</a>
                                <a href="{{ route('admin.pertanyaan.edit', $question) }}" class="mr-2 text-primary hover:underline">Edit</a>
                                <form action="{{ route('admin.pertanyaan.destroy', $question) }}" method="post" class="inline" onsubmit="return confirm('Hapus pertanyaan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center text-on-surface-variant">
                                Belum ada pertanyaan. <a href="{{ route('admin.pertanyaan.create') }}" class="font-medium text-primary">Tambah</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($questions->hasPages())
            <div class="border-t px-5 py-4">{{ $questions->links() }}</div>
        @endif
    </div>
@endsection
