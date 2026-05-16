@extends('admin.layouts.app')
@section('title', 'Detail kriteria')
@section('page-title', 'Detail kriteria')

@section('content')
    <x-admin.page-header
        :title="$criteria->nama_kriteria"
        description="Kategori: {{ $criteria->kategori }}"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Kriteria', 'url' => route('admin.kriteria.index')],
            ['label' => 'Detail', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('admin.kriteria.edit', $criteria) }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Edit</a>
            <a href="{{ route('admin.kriteria.index') }}" class="rounded-lg border border-outline-variant px-4 py-2 text-sm">Kembali</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm">
        <p class="mb-4 whitespace-pre-wrap text-sm text-on-surface">{{ $criteria->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
        <ul class="text-sm text-on-surface-variant">
            <li>Pertanyaan terhubung: <strong class="text-on-surface">{{ $criteria->questions_count }}</strong></li>
            <li>Baris dataset training: <strong class="text-on-surface">{{ $criteria->training_datasets_count }}</strong></li>
        </ul>
    </div>
@endsection
