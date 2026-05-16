@extends('admin.layouts.app')
@section('title', 'Edit kriteria')
@section('page-title', 'Edit kriteria')

@section('content')
    <x-admin.page-header
        title="Edit kriteria"
        :description="$criteria->nama_kriteria"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Kriteria', 'url' => route('admin.kriteria.index')],
            ['label' => 'Edit', 'url' => null],
        ]"
    />

    <div class="max-w-2xl rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.kriteria.update', $criteria) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium" for="nama_kriteria">Nama kriteria</label>
                <input id="nama_kriteria" name="nama_kriteria" type="text" value="{{ old('nama_kriteria', $criteria->nama_kriteria) }}" required class="w-full rounded-lg border border-outline-variant px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                @error('nama_kriteria')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required class="w-full rounded-lg border border-outline-variant px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    @foreach (['minat' => 'Minat', 'bakat' => 'Bakat', 'lainnya' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('kategori', $criteria->kategori) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('kategori')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full rounded-lg border border-outline-variant px-3 py-2">{{ old('deskripsi', $criteria->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary">Perbarui</button>
                <a href="{{ route('admin.kriteria.index') }}" class="rounded-lg border border-outline-variant px-5 py-2.5 text-sm">Batal</a>
            </div>
        </form>
    </div>
@endsection
