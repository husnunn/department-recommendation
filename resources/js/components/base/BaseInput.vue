<script setup lang="ts">
interface Props {
    modelValue?: string | number
    label?: string
    type?: string
    placeholder?: string
    icon?: string
    error?: string
    required?: boolean
    disabled?: boolean
    id?: string
    name?: string
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    modelValue: '',
})

const emit = defineEmits<{
    'update:modelValue': [value: string | number]
}>()
</script>

<template>
    <div class="space-y-2">
        <label
            v-if="label"
            :for="id || name"
            class="block text-[14px] leading-5 tracking-[0.01em] font-medium text-on-surface"
        >
            {{ label }}
            <span v-if="required" class="text-error">*</span>
        </label>
        <div class="relative">
            <span
                v-if="icon"
                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none"
            >{{ icon }}</span>
            <input
                :id="id || name"
                :type="type"
                :name="name"
                :value="modelValue"
                :placeholder="placeholder"
                :required="required"
                :disabled="disabled"
                :class="[
                    'w-full h-[48px] rounded-lg border bg-surface-container-lowest text-on-surface',
                    'text-[16px] leading-6 placeholder:text-outline/70',
                    'focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none',
                    'disabled:opacity-60 disabled:cursor-not-allowed',
                    icon ? 'pl-11 pr-4' : 'px-4',
                    error ? 'border-error focus:border-error focus:ring-error/10' : 'border-outline-variant',
                ]"
                @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
            />
            <slot name="suffix" />
        </div>
        <p v-if="error" class="text-[12px] leading-4 text-error mt-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">error</span>
            {{ error }}
        </p>
    </div>
</template>
