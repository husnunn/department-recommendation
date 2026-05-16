<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'

interface Session {
    id: number
    status: string
    started_at: string | null
    finished_at: string | null
    created_at: string
    failure_message?: string | null
    top_result?: {
        major_name: string
        score: number
    }
}

interface Props {
    sessions: Session[]
}

const props = withDefaults(defineProps<Props>(), {
    sessions: () => [],
})

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric',
    })
}

function formatTime(dateStr: string | null): string {
    if (!dateStr) return ''
    return new Date(dateStr).toLocaleTimeString('id-ID', {
        hour: '2-digit', minute: '2-digit',
    })
}

function statusBadge(status: string) {
    switch (status) {
        case 'completed': return { text: 'Selesai', bg: 'bg-secondary-container/30', color: 'text-secondary', dot: 'bg-secondary' }
        case 'processing': return { text: 'Diproses', bg: 'bg-surface-variant', color: 'text-primary', dot: 'bg-primary' }
        case 'failed': return { text: 'Gagal', bg: 'bg-error-container/50', color: 'text-error', dot: 'bg-error' }
        default: return { text: 'Draft', bg: 'bg-surface-container-high', color: 'text-on-surface-variant', dot: 'bg-outline' }
    }
}
</script>

<template>
    <UserLayout title="Riwayat Tes">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-10 font-semibold text-on-surface tracking-tight mb-1">Riwayat Tes</h2>
                <p class="text-[16px] leading-6 text-on-surface-variant">Lihat semua sesi tes dan hasil rekomendasi Anda.</p>
            </div>
            <Link href="/user/tes">
                <BaseButton icon="add" variant="primary">Mulai Tes Baru</BaseButton>
            </Link>
        </div>

        <!-- Empty State -->
        <BaseCard v-if="sessions.length === 0" padding="lg">
            <div class="text-center py-16">
                <span class="material-symbols-outlined text-[72px] text-outline-variant/50 mb-4">history</span>
                <h3 class="text-[20px] leading-7 font-semibold text-on-surface mb-2">Belum Ada Riwayat</h3>
                <p class="text-[16px] leading-6 text-on-surface-variant mb-6 max-w-md mx-auto">
                    Anda belum pernah mengerjakan tes. Mulai tes pertama Anda untuk mendapatkan rekomendasi jurusan.
                </p>
                <Link href="/user/tes">
                    <BaseButton icon="psychology" variant="primary" size="lg">Mulai Tes Sekarang</BaseButton>
                </Link>
            </div>
        </BaseCard>

        <!-- History Table -->
        <BaseCard v-else padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/50">
                            <th class="py-4 px-6 text-[14px] leading-5 tracking-[0.01em] font-semibold text-on-surface-variant">No</th>
                            <th class="py-4 px-6 text-[14px] leading-5 tracking-[0.01em] font-semibold text-on-surface-variant">Tanggal</th>
                            <th class="py-4 px-6 text-[14px] leading-5 tracking-[0.01em] font-semibold text-on-surface-variant">Status</th>
                            <th class="py-4 px-6 text-[14px] leading-5 tracking-[0.01em] font-semibold text-on-surface-variant">Rekomendasi Utama</th>
                            <th class="py-4 px-6 text-[14px] leading-5 tracking-[0.01em] font-semibold text-on-surface-variant">Skor</th>
                            <th class="py-4 px-6 text-[14px] leading-5 tracking-[0.01em] font-semibold text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        <tr
                            v-for="(session, index) in sessions"
                            :key="session.id"
                            class="hover:bg-surface-container-low/50 transition-colors group"
                        >
                            <td class="py-4 px-6 text-[16px] leading-6 text-on-surface-variant">{{ index + 1 }}</td>
                            <td class="py-4 px-6">
                                <p class="text-[16px] leading-6 text-on-surface">{{ formatDate(session.finished_at || session.created_at) }}</p>
                                <p class="text-[12px] leading-4 text-on-surface-variant">{{ formatTime(session.finished_at || session.created_at) }}</p>
                            </td>
                            <td class="py-4 px-6 align-top">
                                <span
                                    :class="[
                                        'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] leading-4 font-medium',
                                        statusBadge(session.status).bg,
                                        statusBadge(session.status).color,
                                    ]"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="statusBadge(session.status).dot"></span>
                                    {{ statusBadge(session.status).text }}
                                </span>
                                <p
                                    v-if="session.status === 'failed' && session.failure_message"
                                    class="mt-2 max-w-md text-left text-[12px] leading-relaxed text-error"
                                >
                                    {{ session.failure_message }}
                                </p>
                            </td>
                            <td class="py-4 px-6 text-[16px] leading-6 text-on-surface font-medium">
                                {{ session.top_result?.major_name ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span v-if="session.top_result" class="text-[16px] leading-6 text-primary font-semibold">
                                    {{ Number(session.top_result.score).toFixed(2) }}%
                                </span>
                                <span v-else class="text-[16px] leading-6 text-on-surface-variant">-</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div v-if="session.status === 'completed'" class="flex flex-col items-end gap-2 sm:flex-row sm:items-center">
                                    <Link
                                        :href="`/user/hasil/${session.id}`"
                                        class="inline-flex items-center gap-1 text-[14px] font-medium text-primary hover:underline"
                                    >
                                        Lihat
                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </Link>
                                    <a
                                        :href="`/user/recommendations/${session.id}/download`"
                                        class="inline-flex items-center gap-1 text-[14px] font-medium text-on-surface-variant hover:text-primary"
                                    >
                                        PDF
                                        <span class="material-symbols-outlined text-[16px]">download</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </BaseCard>
    </UserLayout>
</template>
