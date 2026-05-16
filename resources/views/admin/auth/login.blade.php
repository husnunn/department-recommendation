<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk Admin — MajorRecommend</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="flex min-h-screen flex-col bg-surface font-sans text-on-surface antialiased">

    <nav class="mx-auto flex h-16 w-full max-w-[1280px] items-center justify-between px-4 md:px-10">
        <div class="text-2xl font-bold tracking-tight text-primary">MajorRecommend</div>
        <div class="hidden items-center gap-8 md:flex">
            <div class="flex gap-6">
                <a class="text-sm font-medium text-on-surface-variant transition-colors hover:text-primary" href="#">Bantuan</a>
                <a class="text-sm font-medium text-on-surface-variant transition-colors hover:text-primary" href="#">Tentang</a>
            </div>
            <span class="text-sm font-medium text-primary">Admin</span>
        </div>
    </nav>

    <main class="relative flex flex-grow items-center justify-center overflow-hidden px-4 py-12">
        <div class="pointer-events-none absolute left-1/2 top-0 -z-10 h-full w-full -translate-x-1/2 opacity-30">
            <div class="absolute left-[-10%] top-[-10%] h-[40%] w-[40%] rounded-full bg-primary-fixed blur-[100px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] h-[40%] w-[40%] rounded-full bg-secondary-container blur-[100px]"></div>
        </div>

        <div class="w-full max-w-[480px]">
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-8 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] md:p-10">
                <div class="mb-10 flex flex-col items-center text-center">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-primary-container shadow-lg shadow-primary-container/20">
                        <span class="material-symbols-outlined text-[32px] text-on-primary">admin_panel_settings</span>
                    </div>
                    <h1 class="mb-2 text-3xl font-semibold tracking-tight text-on-surface">Masuk admin</h1>
                    <p class="text-base text-on-surface-variant">Portal pengelolaan data jurusan, kuesioner, dan model ML.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-lg border border-error/20 bg-error-container/30 p-4">
                        <span class="material-symbols-outlined mt-0.5 text-[20px] text-error">error</span>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm leading-relaxed text-error">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-medium tracking-wide text-on-surface-variant" for="admin-username">Username admin</label>
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline">person</span>
                            <input
                                class="w-full rounded-lg border border-outline-variant bg-white py-3 pl-10 pr-4 text-base text-on-surface transition-all placeholder:text-outline-variant focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10"
                                id="admin-username"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="contoh: adminbk"
                                type="text"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium tracking-wide text-on-surface-variant" for="admin-password">Kata sandi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-outline">lock</span>
                            <input
                                class="w-full rounded-lg border border-outline-variant bg-white py-3 pl-10 pr-12 text-base text-on-surface transition-all placeholder:text-outline-variant focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10"
                                id="admin-password"
                                name="password"
                                placeholder="••••••••"
                                type="password"
                                required
                                autocomplete="current-password"
                            />
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-outline transition-colors hover:text-primary focus:outline-none"
                                type="button"
                                onclick="const p = document.getElementById('admin-password'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('span').textContent = p.type === 'password' ? 'visibility' : 'visibility_off';"
                            >
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="group flex cursor-pointer items-center gap-2">
                            <div class="relative flex items-center">
                                <input
                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-outline-variant bg-white transition-all checked:border-primary checked:bg-primary"
                                    type="checkbox"
                                    name="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                />
                                <span class="material-symbols-outlined pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[16px] text-on-primary opacity-0 transition-opacity peer-checked:opacity-100">check</span>
                            </div>
                            <span class="text-sm font-medium text-on-surface-variant transition-colors group-hover:text-on-surface">Ingat saya</span>
                        </label>
                        <a class="text-sm font-semibold text-primary underline-offset-4 hover:underline" href="#">Lupa kata sandi?</a>
                    </div>

                    <button
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-6 py-4 text-lg font-semibold text-on-primary shadow-md shadow-primary/20 transition-all hover:bg-primary active:scale-[0.98]"
                        type="submit"
                    >
                        Masuk ke dashboard
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-8 flex items-center justify-center gap-2 rounded-lg bg-surface-container px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-secondary" style="font-variation-settings: 'FILL' 1">verified_user</span>
                    <span class="text-xs leading-snug text-on-surface-variant">Gunakan akun admin atau superadmin. Siswa tidak dapat mengakses area ini.</span>
                </div>
            </div>

            <div class="mt-6 text-center md:hidden">
                <a class="text-sm font-medium text-on-surface-variant hover:text-primary" href="#">Butuh bantuan?</a>
            </div>
        </div>
    </main>

    <footer class="mx-auto w-full max-w-[1280px] border-t border-outline-variant px-4 py-8 md:flex md:flex-row md:items-center md:justify-between md:px-10">
        <div class="text-xl font-bold text-on-surface">MajorRecommend</div>
        <div class="mt-4 flex flex-wrap justify-center gap-6 md:mt-0">
            <a class="text-xs text-on-surface-variant transition-colors hover:text-primary" href="#">Kebijakan privasi</a>
            <a class="text-xs text-on-surface-variant transition-colors hover:text-primary" href="#">Syarat & ketentuan</a>
            <a class="text-xs text-on-surface-variant transition-colors hover:text-primary" href="#">Pusat bantuan</a>
        </div>
        <div class="mt-4 text-center text-xs text-on-surface-variant md:mt-0">© {{ date('Y') }} Sistem rekomendasi jurusan.</div>
    </footer>
</body>
</html>
