@extends('admin.layouts.app')
@section('title', 'Tambah jurusan')
@section('page-title', 'Tambah jurusan')

@section('content')
    <x-admin.page-header
        title="Tambah jurusan"
        description="Nama jurusan wajib unik. Gunakan nonaktif bila jurusan tidak perlu ditampilkan."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Jurusan', 'url' => route('admin.jurusan.index')],
            ['label' => 'Tambah', 'url' => null],
        ]"
    />

    <div class="max-w-2xl rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.jurusan.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="nama_jurusan">Nama jurusan</label>
                <input
                    id="nama_jurusan"
                    name="nama_jurusan"
                    type="text"
                    value="{{ old('nama_jurusan') }}"
                    required
                    class="w-full rounded-lg border border-outline-variant px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                @error('nama_jurusan')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="deskripsi">Deskripsi</label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    class="w-full rounded-lg border border-outline-variant px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <input
                    id="is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    class="h-4 w-4 rounded border-outline-variant text-primary"
                    @checked(old('is_active', true))
                />
                <label for="is_active" class="text-sm text-on-surface">Aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary hover:bg-primary-container">Simpan</button>
                <a href="{{ route('admin.jurusan.index') }}" class="rounded-lg border border-outline-variant px-5 py-2.5 text-sm font-medium text-on-surface hover:bg-surface-container-low">Batal</a>
            </div>
        </form>
    </div>
@endsection
