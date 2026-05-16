@extends('admin.layouts.app')
@section('title', 'Manajemen admin')
@section('page-title', 'Manajemen admin')

@section('content')
    <x-admin.page-header
        title="Manajemen admin"
        description="Akun dengan peran admin atau superadmin. Password tidak ditampilkan."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Manajemen admin', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <form method="get" action="{{ route('admin.manajemen-admin') }}" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                <div class="relative">
                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari username…"
                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-2 pl-10 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary sm:w-64"
                    />
                </div>
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary">Cari</button>
            </form>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Username</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Email</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Peran</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($users as $user)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="px-5 py-3 font-medium text-on-surface">{{ $user->username }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $user->email ?: '—' }}</td>
                            <td class="px-5 py-3">
                                @if ($user->role === 'superadmin')
                                    <span class="inline-flex rounded-full bg-primary/15 px-2 py-0.5 text-xs font-semibold text-primary">Superadmin</span>
                                @else
                                    <span class="inline-flex rounded-full bg-surface-container-high px-2 py-0.5 text-xs font-semibold text-on-surface-variant">Admin</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-3 text-on-surface-variant">{{ $user->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">admin_panel_settings</span>
                                Tidak ada akun admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="border-t border-outline-variant/30 px-5 py-4">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
