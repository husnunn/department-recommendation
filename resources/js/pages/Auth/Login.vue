<script setup lang="ts">
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'

const showPassword = ref(false)

const form = useForm({
    username: '',
    password: '',
    remember: false,
})

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <GuestLayout title="Masuk">
        <main class="min-h-screen flex w-full">
            <!-- Left Visual Canvas -->
            <section class="hidden lg:flex lg:w-1/2 relative bg-surface-container-high overflow-hidden items-end p-12">
                <!-- Background gradient instead of image -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-container to-surface-tint z-0"></div>
                <!-- Decorative circles -->
                <div class="absolute top-20 right-20 w-64 h-64 rounded-full bg-white/5 blur-sm"></div>
                <div class="absolute bottom-40 left-10 w-96 h-96 rounded-full bg-white/5 blur-sm"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 rounded-full border border-white/10"></div>
                <!-- Brand Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-transparent to-transparent z-10"></div>
                <div class="relative z-20 max-w-lg mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-4xl text-primary-fixed">psychology</span>
                        <span class="text-[32px] leading-10 font-semibold text-white tracking-tight">EduPath ML</span>
                    </div>
                    <h2 class="text-[48px] leading-[1.2] font-bold text-white tracking-tight mb-4">
                        Temukan Potensi Terbaik Anda.
                    </h2>
                    <p class="text-[18px] leading-7 text-surface-variant">
                        Dapatkan rekomendasi jurusan yang akurat berdasarkan minat, bakat, dan nilai akademik Anda menggunakan teknologi machine learning.
                    </p>
                </div>
            </section>

            <!-- Right Form Canvas -->
            <section class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 relative">
                <!-- Mobile Brand -->
                <div class="absolute top-8 left-8 flex lg:hidden items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">psychology</span>
                    <span class="text-[20px] leading-7 font-semibold text-primary">EduPath</span>
                </div>

                <div class="w-full max-w-[440px]">
                    <!-- Form Header -->
                    <header class="mb-10 text-center lg:text-left">
                        <h1 class="text-[32px] leading-10 font-semibold text-on-surface tracking-tight mb-2">Selamat Datang!</h1>
                        <p class="text-[16px] leading-6 text-on-surface-variant">Masuk ke akun untuk melanjutkan tes rekomendasi jurusan.</p>
                    </header>

                    <!-- Login Form -->
                    <form
                        class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-8 shadow-[0_4px_12px_rgba(0,0,0,0.03)] space-y-6"
                        @submit.prevent="submit"
                    >
                        <BaseInput
                            v-model="form.username"
                            label="Username"
                            name="username"
                            icon="person"
                            placeholder="Masukkan username Anda"
                            :error="form.errors.username"
                            required
                        />

                        <div class="space-y-2">
                            <BaseInput
                                v-model="form.password"
                                label="Kata Sandi"
                                name="password"
                                icon="lock"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Masukkan kata sandi"
                                :error="form.errors.password"
                                required
                            >
                                <template #suffix>
                                    <button
                                        type="button"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors"
                                        @click="showPassword = !showPassword"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">
                                            {{ showPassword ? 'visibility' : 'visibility_off' }}
                                        </span>
                                    </button>
                                </template>
                            </BaseInput>
                        </div>

                        <!-- Remember + Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    v-model="form.remember"
                                    type="checkbox"
                                    class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest cursor-pointer"
                                />
                                <span class="text-[12px] leading-4 text-on-surface-variant">Ingat saya</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <BaseButton
                            type="submit"
                            full-width
                            size="lg"
                            icon="arrow_forward"
                            icon-position="right"
                            :loading="form.processing"
                            :disabled="form.processing"
                        >
                            Masuk
                        </BaseButton>

                        <!-- General error -->
                        <p v-if="form.errors.username || form.errors.password" class="text-[12px] leading-4 text-error text-center">
                            Username atau kata sandi salah.
                        </p>
                    </form>

                    <!-- Footer Link -->
                    <div class="mt-8 text-center">
                        <p class="text-[16px] leading-6 text-on-surface-variant">
                            Belum punya akun?
                            <Link
                                href="/register"
                                class="text-primary text-[14px] leading-5 tracking-[0.01em] font-bold hover:text-on-primary-fixed-variant transition-colors ml-1"
                            >
                                Daftar di sini
                            </Link>
                        </p>
                    </div>
                </div>
            </section>
        </main>
    </GuestLayout>
</template>
