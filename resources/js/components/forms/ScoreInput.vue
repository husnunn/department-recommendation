<script setup lang="ts">
import { computed } from 'vue'

interface Props {
    modelValue: string
    label: string
    required?: boolean
    error?: string
}

const props = withDefaults(defineProps<Props>(), {
    required: false,
    error: undefined,
})

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

const localError = computed(() => {
    if (props.error) {
        return props.error
    }
    const v = props.modelValue.trim()
    if (v === '') {
        return props.required ? 'Nilai wajib diisi.' : undefined
    }
    if (!/^\d+(\.\d{1,2})?$/.test(v)) {
        return 'Nilai harus berupa angka (maks. 2 desimal).'
    }
    const n = Number(v)
    if (n < 0) {
        return 'Nilai minimal 0.'
    }
    if (n > 100) {
        return 'Nilai maksimal 100.'
    }

    return undefined
})

function onInput(event: Event) {
    const target = event.target as HTMLInputElement
    emit('update:modelValue', target.value)
}
</script>

<template>
    <div class="space-y-2">
        <label class="block text-[14px] font-medium text-on-surface">
            {{ label }}
            <span v-if="required" class="text-error">*</span>
        </label>
        <input
            :value="modelValue"
            type="text"
            inputmode="decimal"
            autocomplete="off"
            class="w-full h-[48px] px-4 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none"
            :class="localError ? 'border-error' : ''"
            @input="onInput"
        />
        <p v-if="localError" class="text-sm text-error">{{ localError }}</p>
    </div>
</template>
