@extends('admin.layouts.app')
@section('title', 'Edit sekolah')
@section('page-title', 'Edit sekolah')

@section('content')
    <x-admin.page-header
        title="Edit sekolah"
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Sekolah', 'url' => route('admin.sekolah.index')],
            ['label' => 'Edit', 'url' => null],
        ]"
    />

    <div class="max-w-2xl rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.sekolah.update', $school) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="nama_sekolah">Nama sekolah</label>
                <input id="nama_sekolah" name="nama_sekolah" type="text" value="{{ old('nama_sekolah', $school->nama_sekolah) }}" required class="w-full rounded-lg border border-outline-variant px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                @error('nama_sekolah')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="lokasi">Lokasi</label>
                <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi', $school->lokasi) }}" class="w-full rounded-lg border border-outline-variant px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                @error('lokasi')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-outline-variant text-primary" @checked(old('is_active', $school->is_active)) />
                <label for="is_active" class="text-sm text-on-surface">Aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary hover:bg-primary-container">Simpan</button>
                <a href="{{ route('admin.sekolah.index') }}" class="rounded-lg border border-outline-variant px-5 py-2.5 text-sm font-medium text-on-surface hover:bg-surface-container-low">Batal</a>
            </div>
        </form>
    </div>
@endsection
