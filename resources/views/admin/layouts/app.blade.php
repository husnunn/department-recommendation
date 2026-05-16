<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — MajorRecommend</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .nav-active .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-background font-sans text-on-background antialiased">
    <div
        id="admin-sidebar-backdrop"
        class="fixed inset-0 z-40 cursor-pointer bg-inverse-surface/45 backdrop-blur-[1px]"
        onclick="document.body.classList.remove('admin-sidebar-open')"
        aria-hidden="true"
    ></div>

    <x-admin.sidebar />

    <div class="flex min-h-screen flex-col md:ml-[280px]">
        <x-admin.navbar :page-title="trim($__env->yieldContent('page-title')) ?: 'Dashboard'" />

        <main class="mx-auto w-full max-w-[1440px] flex-1 px-4 pb-10 pt-[88px] md:px-10">
            <x-admin.flash />
            @yield('content')
        </main>

        <x-admin.footer />
    </div>

    <script>
        document.body.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.body.classList.remove('admin-sidebar-open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
