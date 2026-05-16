@extends('admin.layouts.app')
@section('title', 'Kriteria')
@section('page-title', 'Kriteria minat & bakat')

@section('content')
    <x-admin.page-header
        title="Kriteria minat & bakat"
        description="Nama kriteria unik. Hapus diblokir jika masih ada pertanyaan atau dataset."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Kriteria', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.kriteria.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
                    <input type="search" name="q" value="{{ $q }}" placeholder="Cari…" class="w-full rounded-lg border border-outline-variant py-2 pl-10 pr-3 text-sm sm:w-56" />
                </div>
                <button type="submit" class="rounded-lg bg-surface px-4 py-2 text-sm font-medium text-primary ring-1 ring-outline-variant">Cari</button>
            </form>
            <a href="{{ route('admin.kriteria.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah kriteria
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low">
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Kategori</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Pertanyaan</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-outline">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($criteria as $c)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="px-5 py-3 font-medium">{{ $c->nama_kriteria }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $c->kategori }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $c->questions_count }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-right">
                                <a href="{{ route('admin.kriteria.show', $c) }}" class="mr-2 text-primary hover:underline">Detail</a>
                                <a href="{{ route('admin.kriteria.edit', $c) }}" class="mr-2 text-primary hover:underline">Edit</a>
                                <form action="{{ route('admin.kriteria.destroy', $c) }}" method="post" class="inline" onsubmit="return confirm('Hapus kriteria ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center text-on-surface-variant">
                                Belum ada kriteria. <a href="{{ route('admin.kriteria.create') }}" class="font-medium text-primary hover:underline">Tambah</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($criteria->hasPages())
            <div class="border-t px-5 py-4">{{ $criteria->links() }}</div>
        @endif
    </div>
@endsection
