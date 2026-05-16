<script setup lang="ts">
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'

const showPassword = ref(false)
const agreeTerms = ref(false)

const form = useForm({
    nama: '',
    username: '',
    password: '',
    password_confirmation: '',
})

function submit() {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <GuestLayout title="Daftar">
        <main class="min-h-screen flex w-full">
            <!-- Left Visual Canvas -->
            <section class="hidden lg:flex lg:w-1/2 relative bg-surface-container-high overflow-hidden items-end p-12">
                <!-- Background gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-container via-primary to-surface-tint z-0"></div>
                <!-- Decorative elements -->
                <div class="absolute top-32 right-16 w-48 h-48 rounded-full bg-white/5 blur-sm"></div>
                <div class="absolute bottom-20 left-20 w-72 h-72 rounded-full bg-white/5 blur-sm"></div>
                <div class="absolute top-1/3 right-1/3 w-64 h-64 rounded-full border border-white/10"></div>
                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/40 to-transparent z-10"></div>
                <div class="relative z-20 max-w-lg mb-8 text-on-primary">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-4xl text-primary-fixed">psychology</span>
                        <span class="text-[32px] leading-10 font-semibold text-white tracking-tight">EduPath ML</span>
                    </div>
                    <h2 class="text-[48px] leading-[1.2] font-bold text-white tracking-tight mb-4">
                        Peta Jalan Akademik Cerdas Anda.
                    </h2>
                    <p class="text-[18px] leading-7 text-surface-variant">
                        Bergabunglah dengan ribuan siswa yang telah menemukan potensi sesungguhnya melalui rekomendasi jurusan berbasis machine learning yang akurat dan personal.
                    </p>
                </div>
            </section>

            <!-- Right Form Canvas -->
            <section class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 relative">
                <!-- Mobile Brand -->
                <div class="absolute top-8 left-8 flex lg:hidden items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">psychology</span>
                    <span class="text-[20px] leading-7 font-semibold text-primary">EduPath</span>
                </div>

                <div class="w-full max-w-[440px]">
                    <!-- Form Header -->
                    <header class="mb-8 text-center lg:text-left">
                        <h1 class="text-[32px] leading-10 font-semibold text-on-surface tracking-tight mb-2">Buat Akun Baru</h1>
                        <p class="text-[16px] leading-6 text-on-surface-variant">Lengkapi data diri untuk memulai tes minat dan bakat.</p>
                    </header>

                    <!-- Registration Form -->
                    <form
                        class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-8 shadow-[0_4px_12px_rgba(0,0,0,0.03)] space-y-5"
                        @submit.prevent="submit"
                    >
                        <BaseInput
                            v-model="form.nama"
                            label="Nama Lengkap"
                            name="nama"
                            icon="person"
                            placeholder="Sesuai kartu pelajar"
                            :error="form.errors.nama"
                            required
                        />

                        <BaseInput
                            v-model="form.username"
                            label="Username"
                            name="username"
                            icon="alternate_email"
                            placeholder="Buat username unik"
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
                                placeholder="Minimal 8 karakter"
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

                        <BaseInput
                            v-model="form.password_confirmation"
                            label="Konfirmasi Kata Sandi"
                            name="password_confirmation"
                            icon="lock"
                            type="password"
                            placeholder="Ulangi kata sandi"
                        />

                        <!-- Terms -->
                        <div class="flex items-start gap-3 pt-2">
                            <input
                                v-model="agreeTerms"
                                id="terms"
                                type="checkbox"
                                class="mt-1 w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest cursor-pointer"
                            />
                            <label for="terms" class="text-[12px] leading-4 text-on-surface-variant cursor-pointer">
                                Saya menyetujui <a href="#" class="text-primary hover:underline font-medium">Syarat &amp; Ketentuan</a>
                                serta <a href="#" class="text-primary hover:underline font-medium">Kebijakan Privasi</a> yang berlaku.
                            </label>
                        </div>

                        <!-- Submit -->
                        <BaseButton
                            type="submit"
                            full-width
                            size="lg"
                            icon="arrow_forward"
                            icon-position="right"
                            :loading="form.processing"
                            :disabled="form.processing || !agreeTerms"
                        >
                            Daftar Sekarang
                        </BaseButton>
                    </form>

                    <!-- Footer Link -->
                    <div class="mt-8 text-center">
                        <p class="text-[16px] leading-6 text-on-surface-variant">
                            Sudah memiliki akun?
                            <Link
                                href="/login"
                                class="text-primary text-[14px] leading-5 tracking-[0.01em] font-bold hover:text-on-primary-fixed-variant transition-colors ml-1"
                            >
                                Masuk di sini
                            </Link>
                        </p>
                    </div>
                </div>
            </section>
        </main>
    </GuestLayout>
</template>
