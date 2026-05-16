@extends('admin.layouts.app')
@section('title', 'Mata pelajaran')
@section('page-title', 'Mata pelajaran')

@section('content')
    <x-admin.page-header
        title="Mata pelajaran"
        description="CRUD mapel: nama unik, flag wajib, dan status aktif."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Mata pelajaran', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.mapel.index') }}" class="flex flex-wrap gap-2">
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari…" class="rounded-lg border py-2 pl-3 pr-3 text-sm sm:w-64" />
                <button type="submit" class="rounded-lg border px-4 py-2 text-sm">Cari</button>
            </form>
            <a href="{{ route('admin.mapel.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah mapel
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border bg-surface shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-surface-container-low">
                <tr>
                    <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Nama</th>
                    <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Wajib</th>
                    <th class="px-5 py-3 text-xs font-semibold uppercase text-outline">Aktif</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-outline">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($subjects as $subject)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $subject->nama_mapel }}</td>
                        <td class="px-5 py-3">{{ $subject->is_required ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-5 py-3">{{ $subject->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="whitespace-nowrap px-5 py-3 text-right">
                            <a href="{{ route('admin.mapel.show', $subject) }}" class="mr-2 text-primary hover:underline">Detail</a>
                            <a href="{{ route('admin.mapel.edit', $subject) }}" class="mr-2 text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.mapel.destroy', $subject) }}" method="post" class="inline" onsubmit="return confirm('Hapus mapel ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-14 text-center text-on-surface-variant">
                            Belum ada data. <a href="{{ route('admin.mapel.create') }}" class="font-medium text-primary">Tambah</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($subjects->hasPages())
            <div class="border-t px-5 py-4">{{ $subjects->links() }}</div>
        @endif
    </div>
@endsection
