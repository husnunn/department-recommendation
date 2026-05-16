<script setup lang="ts">
import BaseBadge from '@/components/base/BaseBadge.vue'

interface Props {
    title: string
    description?: string
    complete: boolean
}

defineProps<Props>()
</script>

<template>
    <div class="flex items-start gap-3 rounded-xl border border-outline-variant/30 bg-surface p-4">
        <span
            class="material-symbols-outlined mt-0.5 text-[22px]"
            :class="complete ? 'text-secondary' : 'text-outline-variant'"
        >
            {{ complete ? 'check_circle' : 'radio_button_unchecked' }}
        </span>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-on-surface">{{ title }}</p>
            <p v-if="description" class="mt-0.5 text-xs text-on-surface-variant">{{ description }}</p>
            <div class="mt-2">
                <BaseBadge :variant="complete ? 'success' : 'warning'">
                    {{ complete ? 'Selesai' : 'Perlu dilengkapi' }}
                </BaseBadge>
            </div>
        </div>
    </div>
</template>
