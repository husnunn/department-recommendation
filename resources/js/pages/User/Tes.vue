<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { profil } from '@/routes/user'
import UserLayout from '@/layouts/UserLayout.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'

interface Question {
    id: number
    pertanyaan: string
    criteria: { id: number; nama_kriteria: string; kategori: string }
    options: Array<{ id: number; opsi: string; score: number }>
}

interface ExistingAnswer {
    question_option_id: number
    answer: number
}

interface Props {
    questions: Question[]
    sessionId?: number
    existingAnswers?: Record<number, ExistingAnswer>
    profileNameComplete?: boolean
    profileSuggestedComplete?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    questions: () => [],
    existingAnswers: () => ({}),
    profileNameComplete: true,
    profileSuggestedComplete: true,
})

const page = usePage()

const validationMessages = computed(() => {
    const errs = page.props.errors as Record<string, string | string[] | undefined>
    if (! errs) {
        return [] as string[]
    }
    const messages: string[] = []
    for (const v of Object.values(errs)) {
        if (Array.isArray(v)) {
            messages.push(...v)
        } else if (typeof v === 'string') {
            messages.push(v)
        }
    }

    return messages
})

const currentIndex = ref(0)
const answers = ref<Record<number, { optionId: number; score: number }>>({})

const currentQuestion = computed(() => props.questions[currentIndex.value])
const totalQuestions = computed(() => props.questions.length)
const progress = computed(() => ((currentIndex.value + 1) / totalQuestions.value) * 100)
const answeredCount = computed(() => Object.keys(answers.value).length)
const allAnswered = computed(() => answeredCount.value === totalQuestions.value)

const likertLabels = ['Sangat Tidak Setuju', 'Tidak Setuju', 'Netral', 'Setuju', 'Sangat Setuju']

function selectAnswer(questionId: number, optionId: number, score: number) {
    answers.value[questionId] = { optionId, score }
}

function next() {
    if (currentIndex.value < totalQuestions.value - 1) {
        currentIndex.value++
    }
}

function prev() {
    if (currentIndex.value > 0) {
        currentIndex.value--
    }
}

function goToQuestion(index: number) {
    currentIndex.value = index
}

const showUnansweredWarning = computed(() => {
    const q = currentQuestion.value
    if (!q) {
        return false
    }

    return !answers.value[q.id]
})

onMounted(() => {
    const initial: Record<number, { optionId: number; score: number }> = {}
    for (const [qid, row] of Object.entries(props.existingAnswers)) {
        initial[Number(qid)] = {
            optionId: row.question_option_id,
            score: row.answer,
        }
    }
    answers.value = initial
})

const submitting = ref(false)

function submit() {
    submitting.value = true
    const payload = Object.entries(answers.value).map(([questionId, data]) => ({
        question_id: Number(questionId),
        question_option_id: data.optionId,
        answer: data.score,
    }))

    router.post('/user/tes/submit', {
        session_id: props.sessionId,
        answers: payload,
    }, {
        onFinish: () => { submitting.value = false },
    })
}
</script>

<template>
    <UserLayout title="Tes Minat & Bakat">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-[32px] leading-10 font-semibold text-on-surface tracking-tight mb-1">Kuesioner Minat & Bakat</h2>
            <p class="text-[16px] leading-6 text-on-surface-variant">Jawab setiap pertanyaan dengan jujur sesuai preferensi Anda.</p>
        </div>

        <div
            v-if="!profileSuggestedComplete && profileNameComplete"
            class="mb-6 rounded-xl border border-tertiary-fixed-dim/50 bg-tertiary-fixed/20 px-4 py-3 text-sm text-on-surface"
        >
            <p>
                <span class="font-semibold">Disarankan:</span>
                lengkapi NISN, kelas, dan asal sekolah di
                <Link :href="profil.url()" class="font-medium text-primary underline">halaman profil</Link>
                untuk keperluan laporan.
            </p>
        </div>

        <div
            v-if="validationMessages.length"
            class="mb-6 rounded-xl border border-error/30 bg-error-container/20 px-4 py-3 text-sm text-error"
            role="alert"
        >
            <p class="font-semibold mb-1">Tidak dapat memproses rekomendasi</p>
            <ul class="list-disc pl-5 space-y-0.5">
                <li v-for="(msg, i) in validationMessages" :key="i">{{ msg }}</li>
            </ul>
            <p class="mt-2 text-on-surface-variant">
                Lengkapi profil dan nilai mapel wajib di halaman Profil bila diminta, lalu kirim ulang jawaban.
            </p>
        </div>

        <!-- Empty state if no questions -->
        <BaseCard v-if="questions.length === 0" padding="lg">
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-[64px] text-outline-variant mb-4">quiz</span>
                <h3 class="text-[20px] leading-7 font-semibold text-on-surface mb-2">Belum Ada Pertanyaan</h3>
                <p class="text-[16px] leading-6 text-on-surface-variant mb-6">Pertanyaan tes belum tersedia. Hubungi admin untuk informasi lebih lanjut.</p>
            </div>
        </BaseCard>

        <template v-else>
            <!-- Progress Bar -->
            <BaseCard padding="sm" class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[14px] leading-5 tracking-[0.01em] font-medium text-on-surface-variant">
                        Pertanyaan {{ currentIndex + 1 }} dari {{ totalQuestions }}
                    </span>
                    <span class="text-[14px] leading-5 tracking-[0.01em] font-medium text-primary">
                        {{ answeredCount }}/{{ totalQuestions }} dijawab
                    </span>
                </div>
                <div class="w-full bg-surface-container-high rounded-full h-2 overflow-hidden">
                    <div
                        class="bg-primary h-full rounded-full transition-all duration-500 ease-out"
                        :style="{ width: progress + '%' }"
                    ></div>
                </div>
            </BaseCard>

            <!-- Question Navigation Dots -->
            <div class="flex flex-wrap gap-1.5 mb-6">
                <button
                    v-for="(q, index) in questions"
                    :key="q.id"
                    :class="[
                        'w-8 h-8 rounded-lg text-[12px] font-medium flex items-center justify-center transition-all duration-200',
                        index === currentIndex
                            ? 'bg-primary text-on-primary shadow-sm scale-110'
                            : answers[q.id]
                                ? 'bg-secondary-container/40 text-secondary border border-secondary/20'
                                : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest',
                    ]"
                    @click="goToQuestion(index)"
                >
                    {{ index + 1 }}
                </button>
            </div>

            <!-- Question Card -->
            <BaseCard padding="lg" class="mb-6">
                <!-- Category Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] leading-4 font-medium bg-primary/10 text-primary border border-primary/20">
                        {{ currentQuestion?.criteria?.nama_kriteria }}
                    </span>
                    <span class="text-[12px] leading-4 text-on-surface-variant capitalize">
                        {{ currentQuestion?.criteria?.kategori }}
                    </span>
                </div>

                <!-- Question Text -->
                <h3 class="text-[20px] leading-7 font-semibold text-on-surface mb-4">
                    {{ currentQuestion?.pertanyaan }}
                </h3>
                <p
                    v-if="showUnansweredWarning"
                    class="mb-6 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2"
                    role="status"
                >
                    Pilih salah satu opsi untuk pertanyaan ini.
                </p>

                <!-- Likert Scale Options -->
                <div class="space-y-3">
                    <button
                        v-for="option in currentQuestion?.options"
                        :key="option.id"
                        type="button"
                        :class="[
                            'w-full flex items-center gap-4 p-4 rounded-xl border-2 transition-all duration-200 text-left group',
                            answers[currentQuestion.id]?.optionId === option.id
                                ? 'border-primary bg-primary/5 shadow-sm'
                                : 'border-outline-variant/50 hover:border-primary/30 hover:bg-surface-container-low',
                        ]"
                        @click="selectAnswer(currentQuestion.id, option.id, option.score)"
                    >
                        <!-- Radio Indicator -->
                        <div
                            :class="[
                                'w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all',
                                answers[currentQuestion.id]?.optionId === option.id
                                    ? 'border-primary'
                                    : 'border-outline-variant group-hover:border-primary/50',
                            ]"
                        >
                            <div
                                v-if="answers[currentQuestion.id]?.optionId === option.id"
                                class="w-2.5 h-2.5 rounded-full bg-primary"
                            ></div>
                        </div>
                        <span
                            :class="[
                                'text-[16px] leading-6 transition-colors',
                                answers[currentQuestion.id]?.optionId === option.id
                                    ? 'text-on-surface font-medium'
                                    : 'text-on-surface-variant group-hover:text-on-surface',
                            ]"
                        >{{ option.opsi }}</span>
                    </button>
                </div>
            </BaseCard>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between">
                <BaseButton
                    variant="outline"
                    icon="arrow_back"
                    :disabled="currentIndex === 0"
                    @click="prev"
                >
                    Sebelumnya
                </BaseButton>

                <div class="flex gap-3">
                    <BaseButton
                        v-if="currentIndex < totalQuestions - 1"
                        icon="arrow_forward"
                        icon-position="right"
                        @click="next"
                    >
                        Selanjutnya
                    </BaseButton>

                    <BaseButton
                        v-if="allAnswered"
                        variant="primary"
                        icon="check_circle"
                        :loading="submitting"
                        :disabled="submitting"
                        @click="submit"
                    >
                        Kirim Jawaban
                    </BaseButton>
                </div>
            </div>
        </template>
    </UserLayout>
</template>
