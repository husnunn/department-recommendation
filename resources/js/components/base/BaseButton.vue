<script setup lang="ts">
interface Props {
    variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger'
    size?: 'sm' | 'md' | 'lg'
    icon?: string
    iconPosition?: 'left' | 'right'
    loading?: boolean
    disabled?: boolean
    type?: 'button' | 'submit' | 'reset'
    fullWidth?: boolean
}

withDefaults(defineProps<Props>(), {
    variant: 'primary',
    size: 'md',
    type: 'button',
    iconPosition: 'left',
    loading: false,
    disabled: false,
    fullWidth: false,
})
</script>

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="[
            'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-all duration-200 active:scale-[0.98] outline-none cursor-pointer',
            'focus:ring-4 focus:ring-primary/10',
            'disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100',
            fullWidth ? 'w-full' : '',
            // Size variants
            size === 'sm' && 'px-3 py-1.5 text-[13px] leading-5',
            size === 'md' && 'px-4 py-2.5 text-[14px] leading-5 tracking-[0.01em]',
            size === 'lg' && 'px-6 py-3 text-[14px] leading-5 tracking-[0.01em]',
            // Color variants
            variant === 'primary' && 'bg-primary text-on-primary hover:bg-on-primary-fixed-variant shadow-sm',
            variant === 'secondary' && 'bg-primary-container text-on-primary-container hover:bg-surface-tint hover:text-white shadow-sm',
            variant === 'outline' && 'bg-surface-container-lowest border border-outline-variant text-on-surface hover:bg-surface-container-low',
            variant === 'ghost' && 'text-primary hover:bg-surface-container-low',
            variant === 'danger' && 'bg-error text-on-error hover:bg-error/90 shadow-sm',
        ]"
    >
        <span
            v-if="loading"
            class="material-symbols-outlined text-[18px] animate-spin"
        >progress_activity</span>
        <span
            v-else-if="icon && iconPosition === 'left'"
            class="material-symbols-outlined text-[18px]"
        >{{ icon }}</span>
        <slot />
        <span
            v-if="icon && iconPosition === 'right' && !loading"
            class="material-symbols-outlined text-[18px]"
        >{{ icon }}</span>
    </button>
</template>
