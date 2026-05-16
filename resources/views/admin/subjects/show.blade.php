@extends('admin.layouts.app')
@section('title', 'Detail mapel')
@section('page-title', 'Detail mata pelajaran')

@section('content')
    <x-admin.page-header
        :title="$subject->nama_mapel"
        description="{{ $subject->is_required ? 'Wajib untuk siswa' : 'Opsional' }} · {{ $subject->is_active ? 'Aktif' : 'Nonaktif' }}"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Mapel', 'url' => route('admin.mapel.index')],
            ['label' => 'Detail', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('admin.mapel.edit', $subject) }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Edit</a>
            <a href="{{ route('admin.mapel.index') }}" class="rounded-lg border px-4 py-2 text-sm">Kembali</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="rounded-2xl border bg-surface p-6 shadow-sm">
        <p class="text-sm text-on-surface-variant">Nilai akademik terhubung: <strong class="text-on-surface">{{ $subject->academic_scores_count }}</strong></p>
    </div>
@endsection
