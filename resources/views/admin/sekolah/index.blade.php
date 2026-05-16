@extends('admin.layouts.app')
@section('title', 'Sekolah')
@section('page-title', 'Sekolah')

@section('content')
    <x-admin.page-header
        title="Direktori sekolah"
        description="Kelola institusi mitra dan konteks asal sekolah siswa."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Sekolah', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.sekolah.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari sekolah…"
                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-2 pl-10 pr-4 text-sm sm:w-64"
                    />
                </div>
                <button type="submit" class="rounded-lg bg-surface px-4 py-2 text-sm font-medium text-primary ring-1 ring-outline-variant hover:bg-surface-container-low">Cari</button>
            </form>
            <a href="{{ route('admin.sekolah.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary shadow-sm hover:bg-primary-container">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah sekolah
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low">
                        <th class="px-6 py-4 font-semibold text-on-surface-variant">Nama sekolah</th>
                        <th class="px-6 py-4 font-semibold text-on-surface-variant">Lokasi</th>
                        <th class="px-6 py-4 font-semibold text-on-surface-variant">Total kelas</th>
                        <th class="px-6 py-4 font-semibold text-on-surface-variant">Status</th>
                        <th class="px-6 py-4 text-right font-semibold text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse ($schools as $school)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="px-6 py-3 font-medium text-on-surface">{{ $school->nama_sekolah }}</td>
                            <td class="px-6 py-3 text-on-surface-variant">{{ $school->lokasi ?? '—' }}</td>
                            <td class="px-6 py-3 text-on-surface-variant">{{ $school->classes_count }}</td>
                            <td class="px-6 py-3">
                                @if ($school->is_active)
                                    <span class="inline-flex rounded-full bg-secondary-container/40 px-2 py-0.5 text-xs font-semibold text-on-secondary-container">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-surface-container-high px-2 py-0.5 text-xs font-semibold text-on-surface-variant">Nonaktif</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-right">
                                <a href="{{ route('admin.sekolah.show', $school) }}" class="mr-2 text-primary hover:underline">Detail</a>
                                <a href="{{ route('admin.sekolah.edit', $school) }}" class="mr-2 text-primary hover:underline">Edit</a>
                                <form action="{{ route('admin.sekolah.destroy', $school) }}" method="post" class="inline" onsubmit="return confirm('Hapus sekolah ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-3 block text-5xl text-outline-variant/50">school</span>
                                <p class="mb-1 text-lg font-medium text-on-surface">Belum ada sekolah</p>
                                <p class="text-sm">Tambahkan sekolah mitra pertama.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($schools->hasPages())
            <div class="border-t border-outline-variant/30 px-6 py-4">{{ $schools->links() }}</div>
        @endif
    </div>
@endsection
