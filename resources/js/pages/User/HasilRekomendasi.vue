<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { riwayat, tes } from '@/routes/user'
import UserLayout from '@/layouts/UserLayout.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'

interface Result {
    rank: number
    major: {
        nama_jurusan: string
        deskripsi: string | null
    }
    score: number
}

interface Props {
    student: {
        nama: string
    }
    session: {
        id: number
        status: string
        finished_at: string | null
    }
    results: Result[]
}

const props = defineProps<Props>()

const pdfDownloadUrl = computed(() => `/user/recommendations/${props.session.id}/download`)

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric',
    })
}

function displayPercent(score: number): string {
    return `${Number(score).toFixed(2)}%`
}

function scoreBarWidth(score: number): string {
    const w = Math.min(100, Math.max(0, score))

    return `${w}%`
}

function rankColor(rank: number) {
    switch (rank) {
        case 1: return { bg: 'bg-primary', light: 'bg-primary/10', text: 'text-primary', border: 'border-primary' }
        case 2: return { bg: 'bg-secondary', light: 'bg-secondary/10', text: 'text-secondary', border: 'border-secondary' }
        default: return { bg: 'bg-tertiary', light: 'bg-tertiary/10', text: 'text-tertiary', border: 'border-tertiary' }
    }
}
</script>

<template>
    <UserLayout title="Hasil Rekomendasi">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-10 font-semibold text-on-surface tracking-tight mb-1">Hasil Rekomendasi</h2>
                <p class="text-[16px] leading-6 text-on-surface-variant">
                    Halo, <span class="font-medium text-on-surface">{{ student.nama }}</span> — sesi tes tanggal {{ formatDate(session.finished_at) }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a
                    :href="pdfDownloadUrl"
                    class="inline-flex items-center gap-2 rounded-lg border border-outline-variant px-4 py-2 text-sm font-medium text-primary hover:bg-surface-container-low"
                >
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Unduh PDF
                </a>
                <Link :href="riwayat.url()">
                    <BaseButton variant="outline" icon="arrow_back">Riwayat</BaseButton>
                </Link>
                <Link :href="tes.url()">
                    <BaseButton variant="outline" icon="psychology">Kuesioner lagi</BaseButton>
                </Link>
            </div>
        </div>

        <!-- Primary Recommendation (Rank 1) -->
        <BaseCard
            v-if="results.length > 0"
            padding="lg"
            class="mb-8 relative overflow-hidden border-t-4 border-t-primary"
        >
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-primary/5 rounded-full pointer-events-none"></div>
            <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-primary/5 rounded-full pointer-events-none"></div>

            <div class="flex items-start gap-2 mb-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] leading-4 font-bold bg-primary text-on-primary">
                    #1 REKOMENDASI UTAMA
                </span>
            </div>

            <h3 class="text-[32px] leading-10 font-bold text-on-surface tracking-tight mb-3 mt-4">
                {{ results[0].major.nama_jurusan }}
            </h3>
            <p v-if="results[0].major.deskripsi" class="text-[16px] leading-6 text-on-surface-variant mb-6 max-w-2xl">
                {{ results[0].major.deskripsi }}
            </p>

            <!-- Score Bar -->
            <div class="mt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-[14px] leading-5 tracking-[0.01em] font-medium text-on-surface-variant">Persentase Kecocokan</span>
                    <span class="text-[20px] leading-7 font-bold text-primary">{{ displayPercent(results[0].score) }}</span>
                </div>
                <div class="w-full bg-surface-container-high rounded-full h-3 overflow-hidden">
                    <div
                        class="bg-primary h-full rounded-full transition-all duration-1000 ease-out relative"
                        :style="{ width: scoreBarWidth(results[0].score) }"
                    >
                        <div class="absolute inset-0 bg-white opacity-20 bg-[length:10px_10px] bg-[linear-gradient(45deg,rgba(255,255,255,0.15)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.15)_50%,rgba(255,255,255,0.15)_75%,transparent_75%,transparent)]"></div>
                    </div>
                </div>
            </div>
        </BaseCard>

        <!-- Other Recommendations -->
        <div v-if="results.length > 1" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <BaseCard
                v-for="result in results.slice(1)"
                :key="result.rank"
                hoverable
                :class="['border-t-4', rankColor(result.rank).border]"
            >
                <div class="flex items-center gap-2 mb-3">
                    <span
                        :class="[
                            'inline-flex items-center justify-center w-7 h-7 rounded-full text-[12px] font-bold text-white',
                            rankColor(result.rank).bg,
                        ]"
                    >
                        #{{ result.rank }}
                    </span>
                    <span class="text-[14px] leading-5 tracking-[0.01em] font-medium text-on-surface-variant">
                        Rekomendasi {{ result.rank === 2 ? 'Kedua' : 'Ketiga' }}
                    </span>
                </div>

                <h4 class="text-[20px] leading-7 font-semibold text-on-surface mb-2">
                    {{ result.major.nama_jurusan }}
                </h4>
                <p v-if="result.major.deskripsi" class="text-[14px] leading-5 text-on-surface-variant mb-4 line-clamp-2">
                    {{ result.major.deskripsi }}
                </p>

                <!-- Score Bar -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-[12px] leading-4 text-on-surface-variant">Persentase Kecocokan</span>
                        <span :class="['text-[14px] leading-5 font-bold', rankColor(result.rank).text]">
                            {{ displayPercent(result.score) }}
                        </span>
                    </div>
                    <div class="w-full bg-surface-container-high rounded-full h-2 overflow-hidden">
                        <div
                            :class="['h-full rounded-full transition-all duration-1000 ease-out', rankColor(result.rank).bg]"
                            :style="{ width: scoreBarWidth(result.score) }"
                        ></div>
                    </div>
                </div>
            </BaseCard>
        </div>

        <!-- Summary Card -->
        <BaseCard padding="lg">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-primary text-[24px]">info</span>
                <h3 class="text-[20px] leading-7 font-semibold text-on-surface">Catatan Penting</h3>
            </div>
            <p class="text-[16px] leading-6 text-on-surface-variant">
                Hasil ini adalah rekomendasi berdasarkan data kuesioner dan nilai akademik.
                Keputusan akhir tetap dapat mempertimbangkan minat pribadi, konsultasi guru BK, dan pilihan kampus.
            </p>
        </BaseCard>
    </UserLayout>
</template>
