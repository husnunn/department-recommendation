<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

interface Props {
    title?: string
}

defineProps<Props>()
</script>

<template>
    <Head :title="title" />
    <div class="min-h-screen bg-surface font-sans text-on-surface antialiased selection:bg-primary/20 selection:text-primary">
        <slot />
    </div>
</template>
