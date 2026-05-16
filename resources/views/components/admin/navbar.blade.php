@props([
    'pageTitle' => 'Dashboard',
])

<header
    class="fixed right-0 top-0 z-30 flex h-16 items-center justify-between border-b border-outline-variant/40 bg-surface px-4 md:left-[280px] md:px-8"
>
    <div class="flex min-w-0 flex-1 items-center gap-4">
        <button
            type="button"
            class="rounded-full p-2 text-on-surface-variant transition-colors hover:bg-surface-container-low md:hidden"
            onclick="document.body.classList.toggle('admin-sidebar-open')"
            aria-label="Buka menu"
        >
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="relative hidden max-w-md flex-1 md:block">
            <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline-variant">search</span>
            <input
                type="search"
                name="q"
                class="w-full max-w-xs rounded-full border-none bg-surface-container-low py-2 pl-10 pr-4 text-sm text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary md:max-w-md"
                placeholder="Cari data..."
                disabled
                title="Pencarian global akan dihubungkan ke modul terkait"
            />
        </div>
        <span class="truncate text-base font-semibold text-on-surface md:hidden">{{ $pageTitle }}</span>
    </div>
    <div class="flex flex-shrink-0 items-center gap-1 md:gap-2">
        <span class="hidden text-sm font-medium text-on-surface-variant md:inline md:max-w-[200px] md:truncate lg:max-w-xs">
            {{ $pageTitle }}
        </span>
        <button
            type="button"
            class="rounded-full p-2 text-on-surface-variant transition-colors hover:bg-surface-container-low"
            aria-label="Notifikasi"
        >
            <span class="material-symbols-outlined text-[22px]">notifications</span>
        </button>
        <button
            type="button"
            class="rounded-full p-2 text-on-surface-variant transition-colors hover:bg-surface-container-low"
            aria-label="Pengaturan"
        >
            <span class="material-symbols-outlined text-[22px]">settings</span>
        </button>
        <div
            class="ml-1 flex h-9 w-9 items-center justify-center overflow-hidden rounded-full border border-outline-variant bg-primary-container text-on-primary-container"
            title="Akun admin"
        >
            <span class="material-symbols-outlined text-[22px]">person</span>
        </div>
    </div>
</header>
