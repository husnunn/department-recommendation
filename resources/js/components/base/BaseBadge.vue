<script setup lang="ts">
type Variant = 'success' | 'warning' | 'error' | 'neutral' | 'info'

interface Props {
    variant?: Variant
}

withDefaults(defineProps<Props>(), {
    variant: 'neutral',
})

const variantClasses: Record<Variant, string> = {
    success: 'bg-secondary-container/30 text-secondary',
    warning: 'bg-tertiary-container/40 text-on-tertiary-container',
    error: 'bg-error-container/50 text-error',
    neutral: 'bg-surface-container-high text-on-surface-variant',
    info: 'bg-primary/10 text-primary',
}
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[12px] font-medium leading-4',
            variantClasses[variant],
        ]"
    >
        <slot />
    </span>
</template>
