<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { process } from '@/routes/user/recommendations'
import UserLayout from '@/layouts/UserLayout.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'

interface Props {
    canProcess: boolean
    validationErrors: Record<string, string[]>
    nextStepLinks: Record<string, string>
}

const props = defineProps<Props>()

const page = usePage()

const serverErrors = computed(() => {
    const e = page.props.errors as Record<string, string | string[] | undefined>
    if (!e) {
        return [] as string[]
    }
    const out: string[] = []
    for (const v of Object.values(e)) {
        if (Array.isArray(v)) {
            out.push(...v)
        } else if (typeof v === 'string') {
            out.push(v)
        }
    }

    return out
})

const checklist = computed(() => {
    const errs = props.validationErrors

    return [
        {
            key: 'profil',
            label: 'Nama profil terisi',
            ok: !errs.profil?.length,
            href: props.nextStepLinks.profil,
        },
        {
            key: 'kuesioner',
            label: 'Kuesioner lengkap',
            ok: !errs.kuesioner?.length,
            href: props.nextStepLinks.kuesioner,
        },
        {
            key: 'nilai',
            label: 'Nilai mapel wajib lengkap',
            ok: !errs.nilai_akademik?.length,
            href: props.nextStepLinks.nilai,
        },
        {
            key: 'kriteria',
            label: 'Struktur kriteria server',
            ok: !errs.struktur_kriteria?.length,
            href: null,
        },
        {
            key: 'ml',
            label: 'Layanan ML terkonfigurasi',
            ok: !errs.layanan_ml?.length,
            href: null,
        },
        {
            key: 'model',
            label: 'Model aktif tersedia',
            ok: !errs.model_ml?.length,
            href: null,
            hint: errs.model_ml?.[0] ?? 'Model rekomendasi belum tersedia. Silakan hubungi admin.',
        },
    ]
})

const processing = ref(false)

function runProcess() {
    processing.value = true
    router.post(
        process.url(),
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                processing.value = false
            },
        },
    )
}
</script>

<template>
    <UserLayout title="Proses rekomendasi">
        <div class="mb-6">
            <h2 class="text-[32px] font-semibold tracking-tight text-on-surface">
                Proses rekomendasi jurusan
            </h2>
            <p class="mt-1 text-[16px] text-on-surface-variant">
                Pastikan semua checklist hijau, lalu jalankan proses. Data diambil dari server (bukan dari form ini).
            </p>
        </div>

        <div
            v-if="serverErrors.length"
            class="mb-6 rounded-xl border border-error/30 bg-error-container/20 px-4 py-3 text-sm text-error"
            role="alert"
        >
            <p class="mb-1 font-semibold">Rekomendasi belum dapat diproses</p>
            <ul class="list-disc space-y-0.5 pl-5">
                <li v-for="(msg, i) in serverErrors" :key="i">
                    {{ msg }}
                </li>
            </ul>
            <p class="mt-2 text-on-surface-variant">
                Periksa kembali kuesioner dan nilai akademik.
            </p>
        </div>

        <BaseCard padding="lg" class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-on-surface">
                Checklist kesiapan
            </h3>
            <ul class="space-y-3 text-sm">
                <li
                    v-for="item in checklist"
                    :key="item.key"
                    class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div class="flex items-center gap-2">
                        <span
                            class="material-symbols-outlined text-[20px]"
                            :class="item.ok ? 'text-green-600' : 'text-on-surface-variant'"
                        >
                            {{ item.ok ? 'check_circle' : 'radio_button_unchecked' }}
                        </span>
                        <span :class="item.ok ? 'text-on-surface' : 'text-on-surface-variant'">{{ item.label }}</span>
                    </div>
                    <Link
                        v-if="!item.ok && item.href"
                        :href="item.href"
                        class="text-sm font-medium text-primary hover:underline"
                    >
                        Perbaiki
                    </Link>
                    <span v-else-if="!item.ok && item.hint" class="text-xs text-error">{{ item.hint }}</span>
                </li>
            </ul>
        </BaseCard>

        <BaseCard padding="lg">
            <p v-if="processing" class="mb-4 text-sm font-medium text-primary" role="status">
                Sedang memproses rekomendasi jurusan...
            </p>
            <p class="mb-4 text-sm text-on-surface-variant">
                Tombol memanggil backend untuk menghitung skor, memanggil layanan ML, dan menyimpan Top-3 jurusan.
            </p>
            <BaseButton
                variant="primary"
                icon="psychology"
                :disabled="!canProcess || processing"
                :loading="processing"
                @click="runProcess"
            >
                Proses rekomendasi
            </BaseButton>
        </BaseCard>
    </UserLayout>
</template>
