@extends('admin.layouts.app')
@section('title', 'Jurusan kuliah')
@section('page-title', 'Jurusan kuliah')

@section('content')
    <x-admin.page-header
        title="Jurusan kuliah"
        description="Master data jurusan: tambah, ubah, detail, dan hapus sesuai aturan pemakaian di rekomendasi / dataset."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Jurusan kuliah', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.jurusan.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari nama jurusan…"
                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-2 pl-10 pr-3 text-sm sm:w-56"
                    />
                </div>
                <button type="submit" class="rounded-lg bg-surface px-4 py-2 text-sm font-medium text-primary ring-1 ring-outline-variant hover:bg-surface-container-low">Cari</button>
            </form>
            <a href="{{ route('admin.jurusan.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary shadow-sm hover:bg-primary-container">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah jurusan
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Nama jurusan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Status</th>
                        <th class="hidden px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline md:table-cell">Deskripsi</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-outline">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($majors as $major)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="px-5 py-3 font-medium text-on-surface">{{ $major->nama_jurusan }}</td>
                            <td class="px-5 py-3">
                                @if ($major->is_active)
                                    <span class="inline-flex rounded-full bg-secondary-container/40 px-2 py-0.5 text-xs font-semibold text-on-secondary-container">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-surface-container-high px-2 py-0.5 text-xs font-semibold text-on-surface-variant">Nonaktif</span>
                                @endif
                            </td>
                            <td class="hidden max-w-md truncate px-5 py-3 text-on-surface-variant md:table-cell">{{ Str::limit($major->deskripsi ?? '—', 80) }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-right">
                                <a href="{{ route('admin.jurusan.show', $major) }}" class="mr-2 text-primary hover:underline">Detail</a>
                                <a href="{{ route('admin.jurusan.edit', $major) }}" class="mr-2 text-primary hover:underline">Edit</a>
                                <form
                                    action="{{ route('admin.jurusan.destroy', $major) }}"
                                    method="post"
                                    class="inline"
                                    onsubmit="return confirm('Hapus jurusan ini? Tindakan tidak dapat diurungkan jika diizinkan sistem.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">school</span>
                                Belum ada data jurusan. <a class="font-medium text-primary hover:underline" href="{{ route('admin.jurusan.create') }}">Tambah pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($majors->hasPages())
            <div class="border-t border-outline-variant/30 px-5 py-4">{{ $majors->links() }}</div>
        @endif
    </div>
@endsection
