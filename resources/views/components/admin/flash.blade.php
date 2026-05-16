@if (session('success'))
    <div
        class="mb-6 flex items-start gap-3 rounded-xl border border-secondary/25 bg-secondary-container/20 px-4 py-3 text-on-secondary-container"
        role="status"
    >
        <span class="material-symbols-outlined text-[22px] text-secondary" style="font-variation-settings: 'FILL' 1">check_circle</span>
        <p class="text-sm font-medium leading-relaxed">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div
        class="mb-6 flex items-start gap-3 rounded-xl border border-error/30 bg-error-container/40 px-4 py-3 text-on-error-container"
        role="alert"
    >
        <span class="material-symbols-outlined text-[22px] text-error">error</span>
        <p class="text-sm font-medium leading-relaxed">{{ session('error') }}</p>
    </div>
@endif
