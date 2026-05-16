<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { update } from '@/actions/App/Http/Controllers/User/ProfilController'
import { index as academicScoresIndex } from '@/routes/user/academic-scores'
import UserLayout from '@/layouts/UserLayout.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'

interface StudentRow {
    id: number
    nama: string
    nisn: string | null
    kelas: string | null
    asal_sekolah: string | null
}

interface SchoolOption {
    id: number
    nama_sekolah: string
}

interface ClassOption {
    id: number
    school_id: number
    nama_kelas: string
    jurusan: string | null
    school_name: string
}

const props = defineProps<{
    student: StudentRow | null
    schools?: SchoolOption[]
    classes?: ClassOption[]
}>()

const page = usePage()
const flashSuccess = computed(() => (page.props.flash as { success?: string })?.success)
const flashError = computed(() => (page.props.flash as { error?: string })?.error)

const form = useForm({
    nama: props.student?.nama ?? '',
    nisn: props.student?.nisn ?? '',
    kelas: props.student?.kelas ?? '',
    asal_sekolah: props.student?.asal_sekolah ?? '',
})

const selectedSchoolId = ref<number | ''>('')

function findSchoolIdByName(name: string | null): number | '' {
    if (!name || !props.schools?.length) {
        return ''
    }
    const match = props.schools.find((s) => s.nama_sekolah === name)

    return match?.id ?? ''
}

selectedSchoolId.value = findSchoolIdByName(props.student?.asal_sekolah ?? null)

const filteredClasses = computed(() => {
    if (!props.classes?.length) {
        return []
    }
    if (selectedSchoolId.value === '') {
        return props.classes
    }

    return props.classes.filter((c) => c.school_id === selectedSchoolId.value)
})

watch(selectedSchoolId, (schoolId) => {
    if (schoolId === '') {
        return
    }
    const school = props.schools?.find((s) => s.id === schoolId)
    if (school) {
        form.asal_sekolah = school.nama_sekolah
    }
    const stillValid = filteredClasses.value.some((c) => c.nama_kelas === form.kelas)
    if (!stillValid) {
        form.kelas = ''
    }
})

function onSchoolSelect(event: Event) {
    const value = (event.target as HTMLSelectElement).value
    selectedSchoolId.value = value === '' ? '' : Number(value)
    if (value === '') {
        form.asal_sekolah = ''
        form.kelas = ''
    }
}

function onClassSelect(event: Event) {
    const value = (event.target as HTMLSelectElement).value
    form.kelas = value
}

function submit() {
    form.put(update.url(), {
        preserveScroll: true,
    })
}
</script>

<template>
    <UserLayout title="Profil Saya">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[32px] leading-10 font-semibold text-on-surface tracking-tight mb-1">Profil Saya</h2>
                <p class="text-[16px] leading-6 text-on-surface-variant">Data diri (langkah 5). Nilai mapel diisi di halaman terpisah setelah kuesioner.</p>
            </div>
            <Link
                :href="academicScoresIndex.url()"
                class="inline-flex items-center gap-2 rounded-lg border border-outline-variant px-4 py-2 text-sm font-medium text-primary hover:bg-surface-container-low"
            >
                Nilai akademik
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </Link>
        </div>

        <div
            v-if="flashSuccess"
            class="mb-6 rounded-xl border border-secondary/25 bg-secondary-container/20 px-4 py-3 text-sm text-on-secondary-container"
            role="status"
        >
            {{ flashSuccess }}
        </div>
        <div
            v-if="flashError"
            class="mb-6 rounded-xl border border-error/30 bg-error-container/20 px-4 py-3 text-sm text-error"
            role="alert"
        >
            {{ flashError }}
        </div>

        <form class="space-y-8" @submit.prevent="submit">
            <BaseCard>
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant/30">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[20px]">person</span>
                    </div>
                    <div>
                        <h3 class="text-[20px] leading-7 font-semibold text-on-surface">Data Pribadi</h3>
                        <p class="text-[12px] leading-4 text-on-surface-variant">Nama wajib; NISN, kelas, dan asal sekolah disarankan untuk laporan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <BaseInput
                        v-model="form.nama"
                        label="Nama Lengkap (wajib)"
                        name="nama"
                        icon="person"
                        placeholder="Nama sesuai kartu pelajar"
                        :error="form.errors.nama"
                        required
                    />
                    <BaseInput
                        v-model="form.nisn"
                        label="NISN (disarankan)"
                        name="nisn"
                        icon="badge"
                        placeholder="10 digit NISN"
                        :error="form.errors.nisn"
                    />
                    <div>
                        <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="asal_sekolah">Asal Sekolah (disarankan)</label>
                        <select
                            id="asal_sekolah"
                            :value="selectedSchoolId === '' ? '' : String(selectedSchoolId)"
                            class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-sm text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                            @change="onSchoolSelect"
                        >
                            <option value="">Pilih sekolah</option>
                            <option v-for="school in schools" :key="school.id" :value="school.id">
                                {{ school.nama_sekolah }}
                            </option>
                        </select>
                        <p v-if="!schools?.length" class="mt-1 text-xs text-on-surface-variant">Belum ada daftar sekolah dari admin.</p>
                        <p v-if="form.errors.asal_sekolah" class="mt-1 text-sm text-error">{{ form.errors.asal_sekolah }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-on-surface-variant" for="kelas">Kelas (disarankan)</label>
                        <select
                            id="kelas"
                            :value="form.kelas"
                            class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-sm text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary disabled:opacity-60"
                            :disabled="selectedSchoolId === '' && !!schools?.length"
                            @change="onClassSelect"
                        >
                            <option value="">Pilih kelas</option>
                            <option v-for="cls in filteredClasses" :key="cls.id" :value="cls.nama_kelas">
                                {{ cls.nama_kelas }}{{ cls.jurusan ? ` (${cls.jurusan})` : '' }}
                            </option>
                        </select>
                        <p v-if="form.errors.kelas" class="mt-1 text-sm text-error">{{ form.errors.kelas }}</p>
                    </div>
                </div>
            </BaseCard>

            <div class="flex justify-end gap-3">
                <BaseButton
                    type="submit"
                    icon="save"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Simpan profil
                </BaseButton>
            </div>
        </form>
    </UserLayout>
</template>
