<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const isDarkMode = ref(false)
const router = useRouter()

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

const logout = () => {
  router.push('/login')
}

const setRating = (value: number) => {
  feedbackForm.value.rating = value
  ratingLabel.value = ratingLabels[value - 1]
}

const submitGuest = () => {
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
})
</script>

<template>
  <div
    class="min-h-screen bg-[#f8fafc] dark:bg-[#0f172a] font-body text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
      <!-- Navigation -->
      <header
        class="sticky top-0 z-50 border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-[#0f172a]/90 backdrop-blur-md px-6 md:px-12 py-4 shadow-sm transition-colors duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl shadow-md dark:shadow-none bg-white">
              <img src="/assets/images/loader.gif" alt="Logo" class="h-8 w-8 rounded-md" />
            </div>
            <span class="text-xl font-display font-extrabold tracking-tight text-slate-800 dark:text-white">
              BuTaKa
            </span>
          </div>
          <div class="flex items-center gap-4 md:gap-8">
            <nav class="hidden md:flex items-center gap-6 mr-4">
              <router-link to="/receptionist"
                class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-primary-500 transition-colors">Daftar
                Tamu</router-link>
              <router-link to="/receptionist/new-guest"
                class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-primary-500 transition-colors">Isi
                Buku Tamu</router-link>
            </nav>
            <button @click="logout"
              class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-red-500 transition-colors">
              Logout
            </button>
            <button @click="toggleTheme"
              class="p-2.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-90">
              <span v-if="!isDarkMode" class="material-symbols-outlined">dark_mode</span>
              <span v-else class="material-symbols-outlined">light_mode</span>
            </button>
          </div>
        </div>
      </header>

      <main class="flex-1 flex flex-col items-center py-12 px-6 md:px-12">
        <div class="w-full max-w-5xl space-y-12">

          <!-- Isi Buku Tamu Section -->
          <div
            class="w-full bg-white dark:bg-surface-dark rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-800 transition-colors duration-300 overflow-hidden">
            <div class="p-8 md:p-12 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
              <h2 class="text-2xl font-display font-bold text-slate-800 dark:text-white">Isi Buku Tamu</h2>
              <p class="text-slate-500 dark:text-slate-400 mt-2">Silakan lengkapi data kunjungan Anda agar kami dapat
                melayani dengan baik</p>
            </div>

            <form @submit.prevent="submitGuest" class="p-8 md:p-12 space-y-10">
              <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-3">
                  <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Nama Lengkap</label>
                  <div class="relative group">
                    <span
                      class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined transition-colors group-focus-within:text-primary-500">person</span>
                    <input v-model="guestForm.nama" type="text"
                      class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-[#0f172a] border-none rounded-2xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none text-lg"
                      placeholder="Nama lengkap Anda" required />
                  </div>
                </div>
                <div class="space-y-3">
                  <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Instansi /
                    Perusahaan</label>
                  <div class="relative group">
                    <span
                      class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined transition-colors group-focus-within:text-primary-500">apartment</span>
                    <input v-model="guestForm.instansi" type="text"
                      class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-[#0f172a] border-none rounded-2xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none text-lg"
                      placeholder="Organisasi atau pribadi" required />
                  </div>
                </div>
              </div>

              <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-3">
                  <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Nomor Telepon / WA</label>
                  <div class="relative group">
                    <span
                      class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined transition-colors group-focus-within:text-primary-500">call</span>
                    <input v-model="guestForm.telepon" type="tel"
                      class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-[#0f172a] border-none rounded-2xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none text-lg"
                      placeholder="0812xxxx" required />
                  </div>
                </div>
                <div class="space-y-3">
                  <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Email (Opsional)</label>
                  <div class="relative group">
                    <span
                      class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined transition-colors group-focus-within:text-primary-500">mail</span>
                    <input v-model="guestForm.email" type="email"
                      class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-[#0f172a] border-none rounded-2xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none text-lg"
                      placeholder="nama@email.com" />
                  </div>
                </div>
              </div>

              <div class="space-y-3">
                <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Menemui (Pejabat /
                  Staf)</label>
                <div class="relative group">
                  <span
                    class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined transition-colors group-focus-within:text-primary-500">person_search</span>
                  <input v-model="guestForm.tujuan_staf" type="text"
                    class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-[#0f172a] border-none rounded-2xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none text-lg"
                    placeholder="Siapa yang ingin Anda temui?" required />
                </div>
              </div>

              <div class="space-y-3">
                <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Keperluan Kunjungan</label>
                <textarea v-model="guestForm.keperluan"
                  class="w-full p-8 bg-slate-50 dark:bg-[#0f172a] border-none rounded-3xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none min-h-[160px] text-lg"
                  placeholder="Berikan alasan atau detail kunjungan Anda..." required></textarea>
              </div>

              <button type="submit"
                class="w-full py-6 bg-slate-900 dark:bg-primary-600 hover:bg-slate-800 dark:hover:bg-primary-700 text-white font-black text-xl rounded-2xl shadow-2xl transition-all active:scale-[0.98] flex items-center justify-center gap-4">
                <span class="material-symbols-outlined text-2xl">how_to_reg</span>
                Daftar Kunjungan Sekarang
              </button>
            </form>
          </div>

          <!-- Tulis Feedback Section -->
          <div
            class="w-full bg-white dark:bg-surface-dark rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-800 transition-colors duration-300 overflow-hidden">
            <div class="p-8 md:p-12 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
              <h2 class="text-2xl font-display font-bold text-slate-800 dark:text-white">Tulis Feedback</h2>
              <p class="text-slate-500 dark:text-slate-400 mt-2">Masukan Anda sangat berharga bagi peningkatan layanan
                kami</p>
            </div>

            <form @submit.prevent="submitFeedback" class="p-8 md:p-12 space-y-10">
              <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-3">
                  <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Nama Lengkap</label>
                  <div class="relative group">
                    <span
                      class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined transition-colors group-focus-within:text-primary-500">person</span>
                    <input v-model="feedbackForm.nama" type="text"
                      class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-[#0f172a] border-none rounded-2xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none text-lg"
                      placeholder="Masukkan nama Anda" required />
                  </div>
                </div>
                <div class="space-y-3">
                  <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Instansi /
                    Perusahaan</label>
                  <div class="relative group">
                    <span
                      class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined transition-colors group-focus-within:text-primary-500">apartment</span>
                    <input v-model="feedbackForm.instansi" type="text"
                      class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-[#0f172a] border-none rounded-2xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none text-lg"
                      placeholder="Pekerjaan atau organisasi" />
                  </div>
                </div>
              </div>

              <div
                class="space-y-4 p-8 bg-slate-50 dark:bg-[#0f172a]/50 rounded-[2rem] border border-slate-100 dark:border-slate-800">
                <label class="text-md font-black text-slate-700 dark:text-slate-300 block text-center">Bagaimana
                  kualitas layanan kami?</label>
                <div class="flex justify-center gap-4">
                  <button v-for="i in 5" :key="i" type="button" @click="setRating(i)"
                    :class="['transition-all hover:scale-125', i <= feedbackForm.rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-700']">
                    <span class="material-symbols-outlined text-[60px]"
                      :style="i <= feedbackForm.rating ? 'font-variation-settings: \'FILL\' 1' : ''">star</span>
                  </button>
                </div>
                <p class="text-sm text-slate-400 dark:text-slate-500 text-center uppercase tracking-[0.2em] font-black pt-2"
                  :class="{ 'text-primary-600 dark:text-primary-400': feedbackForm.rating > 0 }">
                  {{ ratingLabel }}
                </p>
              </div>

              <div class="space-y-3">
                <label class="text-sm font-black text-slate-700 dark:text-slate-300 ml-1">Pesan Feedback</label>
                <textarea v-model="feedbackForm.pesan"
                  class="w-full p-8 bg-slate-50 dark:bg-[#0f172a] border-none rounded-3xl focus:ring-2 focus:ring-primary-500/20 text-slate-900 dark:text-white transition-all outline-none min-h-[180px] text-lg"
                  placeholder="Bagikan apa yang Anda rasakan selama berkunjung..." required></textarea>
              </div>

              <button type="submit"
                class="w-full py-6 bg-primary-600 hover:bg-primary-700 text-white font-black text-xl rounded-2xl shadow-2xl transition-all active:scale-[0.98] flex items-center justify-center gap-4">
                <span class="material-symbols-outlined text-2xl">send</span>
                Kirim Feedback Sekarang
              </button>
            </form>
          </div>

        </div>
      </main>

      <footer class="py-16 border-t border-slate-200 dark:border-slate-800 text-center transition-colors duration-300">
        <p class="text-slate-400 dark:text-slate-500 text-sm font-bold tracking-wide">
          © 2026 BuTaKa. All rights reserved.
        </p>
      </footer>
    </div>
  </div>
</template>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
