@extends('admin.layouts.app')
@section('title', $school->nama_sekolah)
@section('page-title', 'Detail sekolah')

@section('content')
    <x-admin.page-header
        :title="$school->nama_sekolah"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Sekolah', 'url' => route('admin.sekolah.index')],
            ['label' => 'Detail', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('admin.sekolah.edit', $school) }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Edit</a>
            <a href="{{ route('admin.kelas.index', ['school_id' => $school->id]) }}" class="rounded-lg border border-outline-variant px-4 py-2 text-sm font-medium text-primary">Lihat kelas</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="max-w-2xl rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm">
        <dl class="space-y-4 text-sm">
            <div>
                <dt class="font-medium text-on-surface-variant">Lokasi</dt>
                <dd class="text-on-surface">{{ $school->lokasi ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-on-surface-variant">Status</dt>
                <dd class="text-on-surface">{{ $school->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-on-surface-variant">Total kelas</dt>
                <dd class="text-on-surface">{{ $school->classes_count }}</dd>
            </div>
        </dl>
    </div>
@endsection
