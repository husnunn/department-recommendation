<script setup lang="ts">
interface Props {
    rank: number
    majorName: string
    description?: string | null
    score: number
    compact?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    compact: false,
})

function displayPercent(score: number): string {
    return `${Number(score).toFixed(2)}%`
}

function rankLabel(rank: number): string {
    if (rank === 1) {
        return 'Rekomendasi utama'
    }
    if (rank === 2) {
        return 'Alternatif 1'
    }
    if (rank === 3) {
        return 'Alternatif 2'
    }

    return `Peringkat ${rank}`
}
</script>

<template>
    <div
        :class="[
            'rounded-xl border border-outline-variant/30 bg-surface p-4',
            rank === 1 && !compact ? 'border-t-4 border-t-primary' : '',
        ]"
    >
        <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">
            {{ rankLabel(rank) }}
        </p>
        <h3
            :class="[
                'mt-2 font-semibold text-on-surface',
                compact ? 'text-lg' : 'text-2xl',
            ]"
        >
            {{ majorName }}
        </h3>
        <p v-if="description && !compact" class="mt-2 line-clamp-3 text-sm text-on-surface-variant">
            {{ description }}
        </p>
        <p class="mt-3 text-sm text-on-surface-variant">
            Persentase kecocokan:
            <span class="font-bold text-primary">{{ displayPercent(score) }}</span>
        </p>
    </div>
</template>
