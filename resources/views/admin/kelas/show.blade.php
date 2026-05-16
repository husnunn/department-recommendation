@extends('admin.layouts.app')
@section('title', $schoolClass->nama_kelas)
@section('page-title', 'Detail kelas')

@section('content')
    <x-admin.page-header
        :title="$schoolClass->nama_kelas"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Kelas', 'url' => route('admin.kelas.index')],
            ['label' => 'Detail', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('admin.kelas.edit', $schoolClass) }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Edit</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="max-w-2xl rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm">
        <dl class="space-y-4 text-sm">
            <div>
                <dt class="font-medium text-on-surface-variant">Sekolah</dt>
                <dd class="text-on-surface">{{ $schoolClass->school->nama_sekolah }}</dd>
            </div>
            <div>
                <dt class="font-medium text-on-surface-variant">Jurusan / penjurusan</dt>
                <dd class="text-on-surface">{{ $schoolClass->jurusan ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-on-surface-variant">Status</dt>
                <dd class="text-on-surface">{{ $schoolClass->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
            </div>
        </dl>
    </div>
@endsection
