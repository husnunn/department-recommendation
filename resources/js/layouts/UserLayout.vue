<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import SideNavBar from '@/components/navigation/SideNavBar.vue'
import TopNavBar from '@/components/navigation/TopNavBar.vue'

interface Props {
    title?: string
}

defineProps<Props>()

const sidebarOpen = ref(false)

const page = usePage()
const user = page.props.auth?.user as any
</script>

<template>
    <Head :title="title" />
    <div class="min-h-screen bg-background font-sans text-on-background antialiased flex">
        <!-- Mobile Overlay -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 bg-on-surface/50 z-40 md:hidden transition-opacity"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <SideNavBar
            :is-open="sidebarOpen"
            @close="sidebarOpen = false"
        />

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 md:ml-64 min-h-screen">
            <TopNavBar
                :user="user"
                @toggle-sidebar="sidebarOpen = !sidebarOpen"
            />

            <!-- Page Content Canvas -->
            <div class="flex-1 p-margin-mobile md:p-margin-desktop max-w-[1280px] mx-auto w-full">
                <slot />
            </div>

            <!-- Footer -->
            <footer class="bg-surface-container-lowest w-full flex flex-col sm:flex-row justify-between items-center px-margin-mobile md:px-margin-desktop py-4 border-t border-outline-variant mt-auto gap-2">
                <div class="text-[12px] leading-4 text-on-surface-variant">
                    © 2024 EduRecommend AI. All Rights Reserved.
                </div>
                <div class="flex gap-4">
                    <a href="#" class="text-[12px] leading-4 text-on-surface-variant hover:text-primary transition-colors opacity-80 hover:opacity-100">Privacy Policy</a>
                    <a href="#" class="text-[12px] leading-4 text-on-surface-variant hover:text-primary transition-colors opacity-80 hover:opacity-100">System Status</a>
                    <a href="#" class="text-[12px] leading-4 text-on-surface-variant hover:text-primary transition-colors opacity-80 hover:opacity-100">Contact Support</a>
                </div>
            </footer>
        </main>
    </div>
</template>
