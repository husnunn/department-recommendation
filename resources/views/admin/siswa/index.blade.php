@extends('admin.layouts.app')
@section('title', 'Siswa')
@section('page-title', 'Data siswa')

@section('content')
    <x-admin.page-header
        title="Manajemen siswa"
        description="Daftar siswa terdaftar beserta akun login (username)."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Data siswa', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.siswa') }}" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant">search</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Nama, NISN, kelas, sekolah…"
                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-2 pl-9 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary sm:w-80"
                    />
                </div>
                <button type="submit" class="rounded-lg bg-primary-container px-4 py-2 text-sm font-medium text-on-primary-container hover:bg-primary hover:text-on-primary">Cari</button>
            </form>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-[0_1px_3px_rgba(0,0,0,0.08)]">
        <div class="flex flex-col gap-4 border-b border-outline-variant/30 bg-surface-container-low/50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <span class="text-sm font-medium text-on-surface-variant">Menampilkan {{ $students->total() }} siswa</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/30 bg-surface-container-low">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">NISN</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Username</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Kelas</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Asal sekolah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($students as $student)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="px-5 py-3 font-medium text-on-surface">{{ $student->nama }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $student->nisn }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $student->user?->username ?? '—' }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $student->kelas ?: '—' }}</td>
                            <td class="max-w-xs truncate px-5 py-3 text-on-surface-variant">{{ $student->asal_sekolah ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-3 block text-5xl text-outline-variant/50">group</span>
                                <p class="mb-1 text-lg font-medium text-on-surface">Belum ada siswa</p>
                                <p class="text-sm">Sesuaikan pencarian atau tunggu registrasi siswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            <div class="border-t border-outline-variant/30 px-5 py-4">{{ $students->links() }}</div>
        @endif
    </div>
@endsection
