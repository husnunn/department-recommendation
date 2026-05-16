@extends('admin.layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
    <x-admin.page-header
        title="Laporan & analitik"
        description="Ringkasan distribusi rekomendasi dan aktivitas siswa. Ekspor besar sebaiknya memakai filter dan antrian."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary shadow-sm transition-colors hover:bg-primary-container"
            >
                <span class="material-symbols-outlined text-lg">download</span>
                Ekspor laporan
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-[0_1px_3px_rgba(0,0,0,0.08)]">
            <h3 class="mb-4 text-xl font-semibold text-on-surface">Distribusi rekomendasi</h3>
            <p class="text-sm text-on-surface-variant">Distribusi jurusan akan tampil setelah ada sesi rekomendasi.</p>
            <div class="mt-8 flex flex-col items-center py-12 text-center">
                <span class="material-symbols-outlined text-5xl text-outline-variant/50">bar_chart</span>
                <p class="mt-2 text-sm text-on-surface-variant">Data belum tersedia</p>
            </div>
        </div>
        <div class="rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-[0_1px_3px_rgba(0,0,0,0.08)]">
            <h3 class="mb-4 text-xl font-semibold text-on-surface">Aktivitas siswa</h3>
            <p class="text-sm text-on-surface-variant">Tren registrasi dan penyelesaian kuesioner / nilai.</p>
            <div class="mt-8 flex flex-col items-center py-12 text-center">
                <span class="material-symbols-outlined text-5xl text-outline-variant/50">show_chart</span>
                <p class="mt-2 text-sm text-on-surface-variant">Data belum tersedia</p>
            </div>
        </div>
    </div>
@endsection
