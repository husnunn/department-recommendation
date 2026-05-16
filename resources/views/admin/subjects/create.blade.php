@extends('admin.layouts.app')
@section('title', 'Tambah mapel')
@section('page-title', 'Tambah mata pelajaran')

@section('content')
    <x-admin.page-header
        title="Tambah mata pelajaran"
        description="Nama mapel wajib unik."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Mapel', 'url' => route('admin.mapel.index')],
            ['label' => 'Tambah', 'url' => null],
        ]"
    />

    <div class="max-w-xl rounded-2xl border bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.mapel.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" for="nama_mapel">Nama mata pelajaran</label>
                <input id="nama_mapel" name="nama_mapel" type="text" value="{{ old('nama_mapel') }}" required class="w-full rounded-lg border px-3 py-2" />
                @error('nama_mapel')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_required" value="0" />
                <input id="is_required" name="is_required" type="checkbox" value="1" class="h-4 w-4" @checked(old('is_required')) />
                <label for="is_required" class="text-sm">Wajib diisi siswa</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4" @checked(old('is_active', true)) />
                <label for="is_active" class="text-sm">Aktif</label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary">Simpan</button>
                <a href="{{ route('admin.mapel.index') }}" class="rounded-lg border px-5 py-2.5 text-sm">Batal</a>
            </div>
        </form>
    </div>
@endsection
