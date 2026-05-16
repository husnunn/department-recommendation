@extends('admin.layouts.app')
@section('title', 'Kelas')
@section('page-title', 'Kelas')

@section('content')
    <x-admin.page-header
        title="Manajemen kelas"
        description="Penugasan kelas per sekolah untuk filter data siswa di admin."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Kelas', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.kelas.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="school_id" class="rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm">
                    <option value="">Semua sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected($schoolId === $school->id)>{{ $school->nama_sekolah }}</option>
                    @endforeach
                </select>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari kelas…" class="rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm sm:w-48" />
                <button type="submit" class="rounded-lg bg-surface px-4 py-2 text-sm font-medium text-primary ring-1 ring-outline-variant">Cari</button>
            </form>
            <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary shadow-sm hover:bg-primary-container">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Buat kelas baru
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container-low">
                    <th class="p-4 font-semibold text-on-surface-variant">Nama kelas</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Jurusan</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Sekolah</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Status</th>
                    <th class="p-4 text-right font-semibold text-on-surface-variant">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                @forelse ($classes as $class)
                    <tr class="hover:bg-surface-bright/80">
                        <td class="p-4 font-medium text-on-surface">{{ $class->nama_kelas }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $class->jurusan ?? '—' }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $class->school->nama_sekolah }}</td>
                        <td class="p-4">
                            @if ($class->is_active)
                                <span class="inline-flex rounded-full bg-secondary-container/40 px-2 py-0.5 text-xs font-semibold text-on-secondary-container">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-surface-container-high px-2 py-0.5 text-xs font-semibold text-on-surface-variant">Nonaktif</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap p-4 text-right">
                            <a href="{{ route('admin.kelas.show', $class) }}" class="mr-2 text-primary hover:underline">Detail</a>
                            <a href="{{ route('admin.kelas.edit', $class) }}" class="mr-2 text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.kelas.destroy', $class) }}" method="post" class="inline" onsubmit="return confirm('Hapus kelas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined mb-3 block text-5xl text-outline-variant/50">groups</span>
                            <p class="text-lg font-medium text-on-surface">Belum ada kelas</p>
                            <p class="mt-1 text-sm"><a href="{{ route('admin.kelas.create') }}" class="text-primary hover:underline">Tambah kelas pertama</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($classes->hasPages())
            <div class="border-t border-outline-variant/30 px-4 py-4">{{ $classes->links() }}</div>
        @endif
    </div>
@endsection
