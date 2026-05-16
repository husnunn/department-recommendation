<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { tes } from '@/routes/user'
import { index as recommendationsIndex } from '@/routes/user/recommendations'
import { store } from '@/routes/user/academic-scores'
import UserLayout from '@/layouts/UserLayout.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import ScoreInput from '@/components/forms/ScoreInput.vue'

interface SubjectRow {
    id: number
    nama_mapel: string
    is_required: boolean
}

interface Props {
    subjects: SubjectRow[]
    academicScores: Record<number, number>
}

const props = defineProps<Props>()

const page = usePage()

const flashSuccess = computed(() => (page.props.flash as { success?: string })?.success)
const flashError = computed(() => (page.props.flash as { error?: string })?.error)

const scoreFields: Record<number, string> = {}
for (const s of props.subjects) {
    const existing = props.academicScores[s.id]
    scoreFields[s.id] = existing !== undefined && existing !== null ? String(existing) : ''
}

const form = useForm({
    scores: scoreFields,
})

const requiredSubjects = computed(() => props.subjects.filter((s) => s.is_required))

function isScoreFilled(value: string): boolean {
    return value.trim() !== ''
}

function isScoreValid(value: string): boolean {
    const v = value.trim()
    if (v === '') {
        return true
    }
    if (!/^\d+(\.\d{1,2})?$/.test(v)) {
        return false
    }
    const n = Number(v)

    return n >= 0 && n <= 100
}

const canSave = computed(() => {
    for (const s of requiredSubjects.value) {
        const v = form.scores[s.id] ?? ''
        if (!isScoreFilled(v) || !isScoreValid(v)) {
            return false
        }
    }
    for (const s of props.subjects) {
        const v = form.scores[s.id] ?? ''
        if (v.trim() !== '' && !isScoreValid(v)) {
            return false
        }
    }

    return true
})

function submit() {
    const payload: Record<number, string | number> = {}
    for (const s of props.subjects) {
        const raw = (form.scores[s.id] ?? '').trim()
        if (raw === '') {
            continue
        }
        payload[s.id] = Number(raw)
    }

    form.transform(() => ({ scores: payload })).post(store.url(), {
        preserveScroll: true,
    })
}
</script>

<template>
    <UserLayout title="Nilai akademik">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] font-semibold tracking-tight text-on-surface">Nilai akademik</h2>
                <p class="mt-1 text-[16px] text-on-surface-variant">
                    Isi setelah kuesioner selesai. Mapel bertanda * wajib; mapel lain boleh dikosongkan.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <Link
                    :href="tes.url()"
                    class="inline-flex items-center gap-2 rounded-lg border border-outline-variant px-4 py-2 text-sm font-medium text-on-surface hover:bg-surface-container-low"
                >
                    Kuesioner
                </Link>
                <Link
                    :href="recommendationsIndex.url()"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-on-primary"
                >
                    Proses rekomendasi
                </Link>
            </div>
        </div>

        <div
            v-if="flashError"
            class="mb-6 rounded-xl border border-error/30 bg-error-container/20 px-4 py-3 text-sm text-error"
            role="alert"
        >
            {{ flashError }}
        </div>

        <div
            v-if="flashSuccess"
            class="mb-6 rounded-xl border border-secondary/25 bg-secondary-container/20 px-4 py-3 text-sm text-on-secondary-container"
            role="status"
        >
            {{ flashSuccess }}
        </div>

        <form class="space-y-8" @submit.prevent="submit">
            <BaseCard>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <ScoreInput
                        v-for="subject in subjects"
                        :key="subject.id"
                        v-model="form.scores[subject.id]"
                        :label="subject.nama_mapel"
                        :required="subject.is_required"
                        :error="form.errors['scores.' + subject.id] as string | undefined"
                    />
                </div>
                <p v-if="form.errors.scores" class="mt-4 text-sm text-error">{{ form.errors.scores }}</p>
            </BaseCard>

            <div class="flex justify-end">
                <BaseButton
                    type="submit"
                    icon="save"
                    :loading="form.processing"
                    :disabled="form.processing || !canSave"
                >
                    Simpan nilai
                </BaseButton>
            </div>
        </form>
    </UserLayout>
</template>
