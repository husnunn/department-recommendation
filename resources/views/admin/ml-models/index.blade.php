@extends('admin.layouts.app')
@section('title', 'Model ML')
@section('page-title', 'Model ML')

@section('content')
    <x-admin.page-header
        title="Versi model machine learning"
        description="Daftar model tersimpan; satu model dapat ditandai aktif untuk inferensi."
        :breadcrumbs="[
            ['label' => 'Beranda', 'url' => route('admin.dashboard')],
            ['label' => 'Model ML', 'url' => null],
        ]"
    >
        <x-slot:actions>
            <a
                href="{{ route('admin.training-model') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary shadow-sm hover:bg-primary-container"
            >
                <span class="material-symbols-outlined text-[18px]">model_training</span>
                Halaman training
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Algoritma</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Versi</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Dilatih</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Aktif</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-outline">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($models as $model)
                        <tr class="hover:bg-surface-bright/80">
                            <td class="px-5 py-3 font-medium text-on-surface">{{ $model->model_name }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $model->algorithm }}</td>
                            <td class="px-5 py-3 text-on-surface-variant">{{ $model->version }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-on-surface-variant">{{ $model->trained_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if ($model->is_active)
                                    <span class="inline-flex rounded-full bg-secondary-container/50 px-2 py-0.5 text-xs font-semibold text-on-secondary-container">Aktif</span>
                                @else
                                    <span class="text-on-surface-variant">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if (! $model->is_active)
                                    <form method="post" action="{{ route('admin.ml-models.activate', $model) }}" class="inline" onsubmit="return confirm('Aktifkan model ini? Model lain akan dinonaktifkan.');">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-primary hover:underline">Aktifkan</button>
                                    </form>
                                @else
                                    <span class="text-on-surface-variant">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-4xl text-outline-variant/50">smart_toy</span>
                                Belum ada model di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($models->hasPages())
            <div class="border-t border-outline-variant/30 px-5 py-4">{{ $models->links() }}</div>
        @endif
    </div>
@endsection
