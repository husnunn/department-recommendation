<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { profil } from '@/routes/user'
import UserLayout from '@/layouts/UserLayout.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import OnboardingStepCard from '@/components/user/OnboardingStepCard.vue'
import RecommendationResultCard from '@/components/user/RecommendationResultCard.vue'
import { hasil } from '@/routes/user'

interface OnboardingStep {
    label: string
    complete: boolean
    route: string
}

interface Onboarding {
    profileNameComplete: boolean
    profileSuggestedComplete: boolean
    questionnaireComplete: boolean
    academicScoresComplete: boolean
    canProcessRecommendation: boolean
    recommendedNextLabel: string
    steps: {
        profil: OnboardingStep
        kuesioner: OnboardingStep
        nilai: OnboardingStep
        rekomendasi: OnboardingStep
    }
}

interface Props {
    student?: {
        nama: string
        kelas: string | null
        asal_sekolah: string | null
        nisn: string | null
    }
    stats?: {
        totalTes: number
        lastTestDate: string | null
    }
    onboarding: Onboarding
    lastRecommendation?: {
        session: { id: number; finished_at: string | null }
        results: Array<{
            rank: number
            score: number
            major: { nama_jurusan: string; deskripsi: string | null }
        }>
    } | null
    recommendedUrl: string
}

const props = defineProps<Props>()
</script>

<template>
    <UserLayout title="Dashboard">
        <div class="mb-8">
            <h2 class="mb-1 text-[32px] font-semibold leading-10 tracking-tight text-on-surface">
                Halo, {{ student?.nama ?? 'Siswa' }}!
            </h2>
            <p class="text-[16px] leading-6 text-on-surface-variant">
                Ikuti langkah di bawah untuk mendapatkan rekomendasi jurusan.
            </p>
        </div>

        <div
            v-if="!onboarding.profileSuggestedComplete && onboarding.profileNameComplete"
            class="mb-6 rounded-xl border border-tertiary-fixed-dim/50 bg-tertiary-fixed/20 px-4 py-3 text-sm text-on-surface"
        >
            <span class="font-semibold">Disarankan:</span>
            lengkapi NISN, kelas, dan asal sekolah di
            <Link :href="profil.url()" class="font-medium text-primary underline">halaman profil</Link>
            untuk keperluan laporan.
        </div>

        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <Link :href="recommendedUrl">
                <BaseButton variant="primary" icon="arrow_forward" icon-position="right">
                    {{ onboarding.recommendedNextLabel }}
                </BaseButton>
            </Link>
            <p class="text-sm text-on-surface-variant">
                {{ stats?.totalTes ?? 0 }} rekomendasi selesai
                <span v-if="stats?.lastTestDate"> · terakhir {{ stats.lastTestDate }}</span>
            </p>
        </div>

        <h3 class="mb-4 text-lg font-semibold text-on-surface">Status kelengkapan</h3>
        <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2">
            <OnboardingStepCard
                title="Profil"
                :description="onboarding.steps.profil.label"
                :complete="onboarding.steps.profil.complete"
            />
            <OnboardingStepCard
                title="Kuesioner minat & bakat"
                :description="onboarding.steps.kuesioner.label"
                :complete="onboarding.steps.kuesioner.complete"
            />
            <OnboardingStepCard
                title="Nilai akademik"
                :description="onboarding.steps.nilai.label"
                :complete="onboarding.steps.nilai.complete"
            />
            <OnboardingStepCard
                title="Proses rekomendasi"
                :description="onboarding.steps.rekomendasi.label"
                :complete="onboarding.canProcessRecommendation"
            />
        </div>

        <div v-if="lastRecommendation?.results?.length" class="mb-8">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-on-surface">Rekomendasi terakhir</h3>
                <Link
                    :href="hasil.url(lastRecommendation.session.id)"
                    class="text-sm font-medium text-primary hover:underline"
                >
                    Lihat detail
                </Link>
            </div>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <RecommendationResultCard
                    v-for="row in lastRecommendation.results"
                    :key="row.rank"
                    :rank="row.rank"
                    :major-name="row.major.nama_jurusan"
                    :description="row.major.deskripsi"
                    :score="row.score"
                    compact
                />
            </div>
        </div>

        <BaseCard padding="lg">
            <h3 class="mb-6 text-lg font-semibold text-on-surface">Cara kerja</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">1</div>
                    <p class="text-sm font-semibold text-on-surface">Profil</p>
                    <p class="text-xs text-on-surface-variant">Nama wajib; data lain disarankan.</p>
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">2</div>
                    <p class="text-sm font-semibold text-on-surface">Kuesioner</p>
                    <p class="text-xs text-on-surface-variant">Jawab semua pertanyaan aktif.</p>
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">3</div>
                    <p class="text-sm font-semibold text-on-surface">Nilai akademik</p>
                    <p class="text-xs text-on-surface-variant">Mapel wajib harus diisi.</p>
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">4</div>
                    <p class="text-sm font-semibold text-on-surface">Proses & hasil</p>
                    <p class="text-xs text-on-surface-variant">Top-3 jurusan dari ML.</p>
                </div>
            </div>
        </BaseCard>
    </UserLayout>
</template>
