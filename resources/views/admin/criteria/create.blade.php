@extends('admin.layouts.app')
@section('title', 'Tambah kriteria')
@section('page-title', 'Tambah kriteria')

@section('content')
    <x-admin.page-header
        title="Tambah kriteria"
        description="Nama kriteria wajib unik."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Kriteria', 'url' => route('admin.kriteria.index')],
            ['label' => 'Tambah', 'url' => null],
        ]"
    />

    <div class="max-w-2xl rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.kriteria.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="nama_kriteria">Nama kriteria</label>
                <input id="nama_kriteria" name="nama_kriteria" type="text" value="{{ old('nama_kriteria') }}" required class="w-full rounded-lg border border-outline-variant px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                @error('nama_kriteria')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required class="w-full rounded-lg border border-outline-variant px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    @foreach (['minat' => 'Minat', 'bakat' => 'Bakat', 'lainnya' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('kategori', 'minat') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('kategori')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full rounded-lg border border-outline-variant px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary">Simpan</button>
                <a href="{{ route('admin.kriteria.index') }}" class="rounded-lg border border-outline-variant px-5 py-2.5 text-sm">Batal</a>
            </div>
        </form>
    </div>
@endsection
