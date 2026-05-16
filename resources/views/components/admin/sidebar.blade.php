@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Jurusan kuliah', 'icon' => 'school', 'route' => 'admin.jurusan.index'],
        ['label' => 'Kriteria minat & bakat', 'icon' => 'psychology', 'route' => 'admin.kriteria.index'],
        ['label' => 'Pertanyaan kuesioner', 'icon' => 'quiz', 'route' => 'admin.pertanyaan.index'],
        ['label' => 'Mata pelajaran', 'icon' => 'book', 'route' => 'admin.mapel.index'],
        ['label' => 'Data siswa', 'icon' => 'group', 'route' => 'admin.siswa'],
        ['label' => 'Sekolah', 'icon' => 'domain', 'route' => 'admin.sekolah.index'],
        ['label' => 'Kelas', 'icon' => 'groups', 'route' => 'admin.kelas.index'],
        ['label' => 'Dataset training', 'icon' => 'database', 'route' => 'admin.dataset-training'],
        ['label' => 'Training model', 'icon' => 'model_training', 'route' => 'admin.training-model'],
        ['label' => 'Model ML', 'icon' => 'smart_toy', 'route' => 'admin.ml-models'],
        ['label' => 'Log training', 'icon' => 'history_edu', 'route' => 'admin.log-training'],
        ['label' => 'Hasil rekomendasi', 'icon' => 'rule', 'route' => 'admin.hasil-rekomendasi'],
        ['label' => 'Laporan', 'icon' => 'assessment', 'route' => 'admin.laporan'],
        ['label' => 'Manajemen admin', 'icon' => 'admin_panel_settings', 'route' => 'admin.manajemen-admin', 'superadmin_only' => true],
    ];
@endphp

<nav
    id="admin-sidebar"
    class="fixed left-0 top-0 z-50 flex h-screen w-[280px] flex-col overflow-y-auto border-r border-outline-variant/30 bg-surface-container-lowest py-4 shadow-sm"
    aria-label="Menu admin"
>
    <div class="mb-8 px-6">
        <h1 class="text-xl font-bold tracking-tight text-primary">MajorRecommend</h1>
        <p class="text-xs font-medium tracking-wide text-outline">Admin Console</p>
        <p class="mt-1 text-[11px] leading-snug text-on-surface-variant">Sistem rekomendasi jurusan kuliah</p>
    </div>

    <ul class="flex flex-1 flex-col gap-0.5 px-3">
        @foreach ($navItems as $item)
            @if (! empty($item['superadmin_only']) && ! auth()->user()->isSuperAdmin())
                @continue
            @endif
            @php
                $routeName = $item['route'];
                $active = request()->routeIs($routeName);
                if (! $active && str_ends_with($routeName, '.index')) {
                    $active = request()->routeIs(\Illuminate\Support\Str::beforeLast($routeName, '.').'.*');
                }
            @endphp
            <li>
                <a
                    href="{{ route($routeName) }}"
                    class="{{ $active
                        ? 'border-primary bg-surface-container-low font-bold text-primary shadow-sm nav-active border-r-4'
                        : 'border-transparent text-on-surface-variant hover:bg-surface-container-low' }} flex cursor-pointer items-center gap-2 rounded-lg border-r-4 px-4 py-2.5 text-sm transition-colors active:scale-[0.98]"
                >
                    <span class="material-symbols-outlined shrink-0 text-[22px]">{{ $item['icon'] }}</span>
                    <span class="leading-snug">{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-auto flex flex-col gap-0.5 border-t border-outline-variant/30 px-3 pt-4">
        <form method="POST" action="{{ route('admin.logout') }}" class="px-1">
            @csrf
            <button
                type="submit"
                class="flex w-full items-center gap-2 rounded-lg px-4 py-3 text-left text-sm font-medium text-error transition-colors hover:bg-error-container/30"
            >
                <span class="material-symbols-outlined text-[22px]">logout</span>
                Keluar
            </button>
        </form>
    </div>
</nav>
