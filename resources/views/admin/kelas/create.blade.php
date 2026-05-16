@extends('admin.layouts.app')
@section('title', 'Tambah kelas')
@section('page-title', 'Tambah kelas')

@section('content')
    <x-admin.page-header
        title="Tambah kelas"
        description="Kelas terikat pada satu sekolah. Nama kelas unik per sekolah."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Kelas', 'url' => route('admin.kelas.index')],
            ['label' => 'Tambah', 'url' => null],
        ]"
    />

    <div class="max-w-2xl rounded-2xl border border-outline-variant/30 bg-surface p-6 shadow-sm md:p-8">
        <form method="post" action="{{ route('admin.kelas.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="school_id">Sekolah</label>
                <select id="school_id" name="school_id" required class="w-full rounded-lg border border-outline-variant px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="">Pilih sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected((int) old('school_id') === $school->id)>{{ $school->nama_sekolah }}</option>
                    @endforeach
                </select>
                @error('school_id')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="nama_kelas">Nama kelas</label>
                <input id="nama_kelas" name="nama_kelas" type="text" value="{{ old('nama_kelas') }}" required placeholder="XII IPA 1" class="w-full rounded-lg border border-outline-variant px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                @error('nama_kelas')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="jurusan">Jurusan / penjurusan</label>
                <input id="jurusan" name="jurusan" type="text" value="{{ old('jurusan') }}" placeholder="IPA, IPS, dll." class="w-full rounded-lg border border-outline-variant px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                @error('jurusan')<p class="mt-1 text-sm text-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-outline-variant text-primary" @checked(old('is_active', true)) />
                <label for="is_active" class="text-sm text-on-surface">Aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-on-primary hover:bg-primary-container">Simpan</button>
                <a href="{{ route('admin.kelas.index') }}" class="rounded-lg border border-outline-variant px-5 py-2.5 text-sm font-medium text-on-surface hover:bg-surface-container-low">Batal</a>
            </div>
        </form>
    </div>
@endsection
