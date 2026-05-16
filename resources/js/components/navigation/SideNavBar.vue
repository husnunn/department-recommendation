<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { dashboard, profil, tes, riwayat } from '@/routes/user'
import { index as academicScoresIndex } from '@/routes/user/academic-scores'
import { index as recommendationsIndex } from '@/routes/user/recommendations'

interface Props {
    isOpen: boolean
}

defineProps<Props>()
const emit = defineEmits<{ close: [] }>()

const page = usePage()

const navItems = [
    { label: 'Dashboard', icon: 'dashboard', href: dashboard.url() },
    { label: 'Profil Saya', icon: 'person', href: profil.url() },
    { label: 'Kuesioner', icon: 'psychology', href: tes.url() },
    { label: 'Nilai Akademik', icon: 'school', href: academicScoresIndex.url() },
    { label: 'Proses Rekomendasi', icon: 'model_training', href: recommendationsIndex.url() },
    { label: 'Riwayat', icon: 'history', href: riwayat.url() },
]

function isActive(href: string): boolean {
    const path = page.url.split('?')[0]
    if (href === recommendationsIndex.url()) {
        return path.startsWith('/user/recommendations')
    }
    if (href === academicScoresIndex.url()) {
        return path.startsWith('/user/academic-scores')
    }

    return path === href || path.startsWith(href + '/')
}
</script>

<template>
    <nav
        :class="[
            'bg-surface-container-lowest shadow-md h-screen w-64 fixed left-0 top-0 flex flex-col py-2 gap-2 z-50 transition-transform duration-300',
            'md:translate-x-0',
            isOpen ? 'translate-x-0' : '-translate-x-full',
        ]"
    >
        <!-- Brand Header -->
        <div class="px-6 py-4 flex flex-col gap-1 border-b border-outline-variant/30 mb-2">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1">psychology</span>
                </div>
                <h1 class="text-[24px] leading-8 font-semibold text-primary tracking-tight">
                    EduPath
                </h1>
            </div>
            <p class="text-[12px] leading-4 text-on-surface-variant ml-11">Smart Career Path</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex-1 px-4 flex flex-col gap-1 overflow-y-auto">
            <Link
                v-for="item in navItems"
                :key="item.href"
                :href="item.href"
                :class="[
                    'flex items-center gap-3 px-4 py-3 rounded-lg text-[14px] leading-5 tracking-[0.01em] font-medium transition-all duration-200',
                    isActive(item.href)
                        ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:translate-x-1',
                ]"
                @click="emit('close')"
            >
                <span
                    class="material-symbols-outlined text-[20px]"
                    :style="isActive(item.href) ? `font-variation-settings: 'FILL' 1` : ''"
                >{{ item.icon }}</span>
                {{ item.label }}
            </Link>
        </div>

        <!-- Bottom Links -->
        <div class="px-4 mt-auto border-t border-outline-variant/30 pt-4 flex flex-col gap-1 mb-2">
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg text-[14px] leading-5 tracking-[0.01em] font-medium">
                <span class="material-symbols-outlined text-[20px]">help</span>
                Bantuan
            </a>
            <Link
                href="/logout"
                method="post"
                as="button"
                class="flex items-center gap-3 px-4 py-2 text-error hover:bg-error-container/50 transition-all rounded-lg text-[14px] leading-5 tracking-[0.01em] font-medium w-full"
            >
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Keluar
            </Link>
        </div>
    </nav>
</template>
