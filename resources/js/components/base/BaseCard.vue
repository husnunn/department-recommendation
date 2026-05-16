<script setup lang="ts">
interface Props {
    padding?: 'none' | 'sm' | 'md' | 'lg'
    shadow?: boolean
    border?: boolean
    hoverable?: boolean
}

withDefaults(defineProps<Props>(), {
    padding: 'md',
    shadow: true,
    border: true,
    hoverable: false,
})
</script>

<template>
    <div
        :class="[
            'bg-surface-container-lowest rounded-xl',
            shadow && 'shadow-[0_4px_12px_rgba(0,0,0,0.05)]',
            border && 'border border-outline-variant/30',
            hoverable && 'hover:shadow-md transition-shadow',
            padding === 'sm' && 'p-4',
            padding === 'md' && 'p-6',
            padding === 'lg' && 'p-8',
            padding === 'none' && '',
        ]"
    >
        <slot />
    </div>
</template>
