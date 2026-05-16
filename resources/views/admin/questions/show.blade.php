@extends('admin.layouts.app')
@section('title', 'Detail pertanyaan')
@section('page-title', 'Detail pertanyaan')

@section('content')
    <x-admin.page-header
        :title="Str::limit($question->pertanyaan, 80)"
        description="Kriteria: {{ $question->criteria?->nama_kriteria ?? '—' }}"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Pertanyaan', 'url' => route('admin.pertanyaan.index')],
            ['label' => 'Detail', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('admin.pertanyaan.edit', $question) }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Edit</a>
            <a href="{{ route('admin.pertanyaan.index') }}" class="rounded-lg border px-4 py-2 text-sm">Kembali</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="mb-6 rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm">
        <p class="whitespace-pre-wrap text-sm text-on-surface">{{ $question->pertanyaan }}</p>
        <p class="mt-3 text-sm text-on-surface-variant">Urutan: {{ $question->sort_order }} · {{ $question->is_active ? 'Aktif' : 'Nonaktif' }}</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="border-b bg-surface-container-low px-5 py-3 text-sm font-semibold">Opsi jawaban</div>
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b text-xs uppercase text-outline">
                    <th class="px-5 py-2">Opsi</th>
                    <th class="px-5 py-2">Skor</th>
                    <th class="px-5 py-2">Urut</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($question->options as $o)
                    <tr>
                        <td class="px-5 py-2">{{ $o->opsi }}</td>
                        <td class="px-5 py-2">{{ $o->score }}</td>
                        <td class="px-5 py-2">{{ $o->sort_order }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
