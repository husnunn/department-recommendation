@extends('admin.layouts.app')
@section('title', 'Detail jurusan')
@section('page-title', 'Detail jurusan')

@section('content')
    <x-admin.page-header
        :title="$major->nama_jurusan"
        description="Ringkasan pemakaian data di sistem."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Jurusan', 'url' => route('admin.jurusan.index')],
            ['label' => 'Detail', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('admin.jurusan.edit', $major) }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Edit</a>
            <a href="{{ route('admin.jurusan.index') }}" class="rounded-lg border border-outline-variant px-4 py-2 text-sm font-medium text-on-surface hover:bg-surface-container-low">Kembali</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-outline">Profil</h2>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-on-surface-variant">Status</dt><dd class="font-medium">{{ $major->is_active ? 'Aktif' : 'Nonaktif' }}</dd></div>
                <div><dt class="text-on-surface-variant">Deskripsi</dt><dd class="whitespace-pre-wrap text-on-surface">{{ $major->deskripsi ?: '—' }}</dd></div>
            </dl>
        </div>
        <div class="rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-outline">Pemakaian</h2>
            <ul class="space-y-2 text-sm text-on-surface">
                <li>Hasil rekomendasi: <strong>{{ $major->recommendation_results_count }}</strong> baris</li>
                <li>Dataset training: <strong>{{ $major->training_datasets_count }}</strong> baris</li>
            </ul>
        </div>
    </div>
@endsection
