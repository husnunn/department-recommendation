@props([
    'title',
    'description' => null,
    'breadcrumbs' => [],
])

@php
    $breadcrumbs = $breadcrumbs ?: [];
@endphp

<div class="mb-8 flex flex-col gap-4 md:mb-10 md:flex-row md:items-end md:justify-between">
    <div class="min-w-0">
        @if (count($breadcrumbs))
            <nav class="mb-2 flex flex-wrap items-center gap-1 text-xs font-medium text-outline" aria-label="Breadcrumb">
                @foreach ($breadcrumbs as $i => $crumb)
                    @if ($i > 0)
                        <span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
                    @endif
                    @if (! empty($crumb['url']) && $i < count($breadcrumbs) - 1)
                        <a href="{{ $crumb['url'] }}" class="transition-colors hover:text-primary">{{ $crumb['label'] }}</a>
                    @else
                        <span class="{{ $i === count($breadcrumbs) - 1 ? 'text-on-surface' : '' }}">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif
        <h1 class="text-2xl font-semibold tracking-tight text-on-surface md:text-[32px] md:leading-10">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-on-surface-variant md:text-base">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex flex-shrink-0 flex-wrap items-center gap-3">{{ $actions }}</div>
    @endisset
</div>
