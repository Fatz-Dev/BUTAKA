<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Moon, Sun } from 'lucide-vue-next'
import { useGuestLogsStore } from '../stores/guestLogs'

const isDarkMode = ref(false)

const guestForm = ref({
    nama: '',
    instansi: '',
    telepon: '',
    email: '',
    tujuan_staf: '',
    keperluan: ''
})

const feedbackForm = ref({
    nama: '',
    instansi: '',
    rating: 0,
    pesan: ''
})

const ratingLabel = ref('Pilih Bintang')
const ratingLabels = ['Sangat Buruk', 'Buruk', 'Biasa Saja', 'Sangat Baik', 'Luar Biasa!']

const toggleTheme = () => {
    const htmlTag = document.documentElement
    isDarkMode.value = !isDarkMode.value
    if (isDarkMode.value) {
        htmlTag.classList.add('dark')
        localStorage.setItem('theme', 'dark')
    } else {
        htmlTag.classList.remove('dark')
        localStorage.setItem('theme', 'light')
    }
}

const setRating = (value: number) => {
    feedbackForm.value.rating = value
    ratingLabel.value = ratingLabels[value - 1]
}

const guestLogsStore = useGuestLogsStore()

const submitGuest = () => {
    guestLogsStore.addGuest({ ...guestForm.value })
    alert('Terima kasih! Kunjungan Anda telah berhasil didaftarkan.')
    guestForm.value = { nama: '', instansi: '', telepon: '', email: '', tujuan_staf: '', keperluan: '' }
}

const submitFeedback = () => {
    alert('Terima kasih atas feedback Anda!')
    feedbackForm.value = { nama: '', instansi: '', rating: 0, pesan: '' }
    ratingLabel.value = 'Pilih Bintang'
}

onMounted(() => {
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        isDarkMode.value = true
        document.documentElement.classList.add('dark')
    }

    // Initialize AOS if available
    if ((window as any).AOS) {
        (window as any).AOS.init();
    }
})
</script>

<template>
    <div class="min-h-screen bg-[#eff6ff] dark:bg-[#151824] font-sans transition-colors duration-300">
        <!-- Header -->
        <header
            class="bg-[#3a57e8] dark:bg-[#222738] text-white py-4 px-6 md:px-12 shadow-md transition-colors duration-300">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-1 rounded-full backdrop-blur-sm">
                        <img src="/assets/images/logo-new.png" alt="BuTaKa Logo" width="48" height="48"
                            class="w-12 h-12 object-contain" />
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight leading-none text-white">BuTaKa</h1>
                        <p class="text-xs text-blue-100 font-medium">Buku Tamu Kantor</p>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button @click="toggleTheme"
                    class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all">
                    <component :is="isDarkMode ? Sun : Moon" :size="20" />
                </button>
            </div>
        </header>

        <main class="max-w-5xl mx-auto py-12 px-6 flex flex-col gap-10">

            <!-- Isi Buku Tamu Section -->
            <section
                class="bg-white dark:!bg-[#222738] rounded-xl shadow-lg border border-slate-100 dark:border-[#30384f] overflow-hidden transition-colors duration-300">
                <div class="px-8 py-6 border-b border-slate-100 dark:border-[#30384f]">
                    <h2 class="text-2xl font-bold text-[#3a57e8] dark:text-blue-400">Isi Buku Tamu</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Silakan lengkapi data kunjungan Anda</p>
                </div>

                <form @submit.prevent="submitGuest" class="p-8 space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">person</span>
                                <input v-model="guestForm.nama" type="text"
                                    class="w-full pl-11 pr-4 py-3 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white"
                                    placeholder="Nama lengkap Anda" required />
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Instansi /
                                Perusahaan (Opsional)</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">apartment</span>
                                <input v-model="guestForm.instansi" type="text"
                                    class="w-full pl-11 pr-4 py-3 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white"
                                    placeholder="Organisasi atau pribadi" />
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Telepon /
                                WA</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">call</span>
                                <input v-model="guestForm.telepon" type="tel"
                                    class="w-full pl-11 pr-4 py-3 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white"
                                    placeholder="0812-xxxx" required />
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email
                                (Opsional)</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">mail</span>
                                <input v-model="guestForm.email" type="email"
                                    class="w-full pl-11 pr-4 py-3 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white"
                                    placeholder="nama@email.com" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Menemui (Pejabat /
                            Staf)</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">person_search</span>
                            <input v-model="guestForm.tujuan_staf" type="text"
                                class="w-full pl-11 pr-4 py-3 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white"
                                placeholder="Siapa yang ingin Anda temui?" required />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Keperluan
                            Kunjungan</label>
                        <textarea v-model="guestForm.keperluan"
                            class="w-full p-4 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white h-32 resize-none"
                            placeholder="Berikan alasan atau detail kunjungan Anda..." required></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-[#3a57e8] hover:bg-blue-700 dark:bg-[#3a57e8] dark:hover:bg-blue-600 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                        Daftar Kunjungan Sekarang
                    </button>
                </form>
            </section>

            <!-- Feedback Section -->
            <section
                class="bg-white dark:!bg-[#222738] rounded-xl shadow-lg border border-slate-100 dark:border-[#30384f] overflow-hidden transition-colors duration-300">
                <div class="px-8 py-6 border-b border-slate-100 dark:border-[#30384f]">
                    <h2 class="text-2xl font-bold text-[#3a57e8] dark:text-blue-400">Tulis Feedback</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Hanya butuh waktu sebentar</p>
                </div>

                <form @submit.prevent="submitFeedback" class="p-8 space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">person</span>
                                <input v-model="feedbackForm.nama" type="text"
                                    class="w-full pl-11 pr-4 py-3 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white"
                                    placeholder="Masukkan nama Anda" required />
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Instansi /
                                Perusahaan</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">apartment</span>
                                <input v-model="feedbackForm.instansi" type="text"
                                    class="w-full pl-11 pr-4 py-3 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white"
                                    placeholder="Pekerjaan atau organisasi" />
                            </div>
                        </div>
                    </div>

                    <div class="text-center space-y-4 py-4">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Bagaimana kualitas layanan
                            kami?</label>
                        <div class="flex justify-center gap-2">
                            <button v-for="i in 5" :key="i" type="button" @click="setRating(i)"
                                class="transition-transform hover:scale-110 focus:outline-none">
                                <span class="material-symbols-outlined text-[40px]"
                                    :class="i <= feedbackForm.rating ? 'text-amber-400 fill-current' : 'text-slate-200 dark:text-slate-700'">star</span>
                            </button>
                        </div>
                        <p class="text-sm font-semibold text-[#3a57e8] dark:text-blue-400 h-6">{{ feedbackForm.rating >
                            0 ? ratingLabel : '' }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pesan Feedback</label>
                        <textarea v-model="feedbackForm.pesan"
                            class="w-full p-4 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white h-32 resize-none"
                            placeholder="Bagikan apa yang Anda rasakan selama berkunjung..." required></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-[#3a57e8] hover:bg-blue-700 dark:bg-[#3a57e8] dark:hover:bg-blue-600 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                        Kirim Feedback Sekarang
                    </button>
                </form>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-[#3a57e8] dark:bg-[#222738] text-white py-8 text-center mt-auto">
            <p class="text-sm opacity-80 font-medium">Project Magang</p>
        </footer>
    </div>
</template>

<style scoped>
.fill-current {
    font-variation-settings: 'FILL' 1;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.text-amber-400.fill-current {
    font-variation-settings: 'FILL' 1;
}
</style>
