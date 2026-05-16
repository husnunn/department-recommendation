<script setup lang="ts">
interface Props {
    user?: {
        username: string
        role: string
    }
}

defineProps<Props>()
const emit = defineEmits<{ toggleSidebar: [] }>()
</script>

<template>
    <header class="bg-surface shadow-sm flex justify-between items-center px-margin-mobile md:px-margin-desktop h-16 w-full sticky top-0 z-30">
        <div class="flex items-center gap-4">
            <!-- Mobile menu toggle -->
            <button
                class="md:hidden text-on-surface-variant p-2 -ml-2 rounded-full hover:bg-surface-container-high transition-colors active:scale-95 duration-100"
                @click="emit('toggleSidebar')"
            >
                <span class="material-symbols-outlined">menu</span>
            </button>
            <!-- Mobile Brand -->
            <span class="text-[24px] leading-8 font-semibold text-primary md:hidden">EduPath</span>
            <!-- Desktop Page Context -->
            <span class="text-[20px] leading-7 font-semibold text-on-surface hidden md:block">
                <slot name="title" />
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button class="p-2 text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full active:scale-95 duration-100">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="p-2 text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full active:scale-95 duration-100">
                <span class="material-symbols-outlined">settings</span>
            </button>
            <!-- Avatar -->
            <div class="h-8 w-8 ml-2 rounded-full bg-primary-container overflow-hidden border border-outline-variant flex items-center justify-center text-on-primary-container">
                <span class="material-symbols-outlined text-[20px]">person</span>
            </div>
        </div>
    </header>
</template>
