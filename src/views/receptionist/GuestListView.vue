<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useGuestLogsStore } from '../../stores/guestLogs'

const guestLogsStore = useGuestLogsStore()
const searchQuery = ref('')
const isDarkMode = ref(false)
const router = useRouter()

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

const confirmCheckout = (id: string, name: string) => {
  if (confirm(`Check-out Tamu? ${name} akan ditandai telah selesai berkunjung.`)) {
    guestLogsStore.checkoutGuest(id)
  }
}

onMounted(() => {
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDarkMode.value = true
    document.documentElement.classList.add('dark')
  }
})

const filteredVisitors = () => {
  let logs = guestLogsStore.logs
  if (!searchQuery.value) return logs
  const query = searchQuery.value.toLowerCase()
  return logs.filter(v =>
    v.nama.toLowerCase().includes(query) ||
    v.instansi.toLowerCase().includes(query)
  )
}
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
        <div class="w-full max-w-6xl space-y-8">
          <div
            class="w-full bg-white dark:bg-surface-dark rounded-[1.5rem] shadow-xl border border-slate-100 dark:border-slate-800 transition-colors duration-300 overflow-hidden">
            <div class="p-6 md:p-10 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                  <h2 class="text-2xl font-display font-bold text-slate-800 dark:text-white">
                    Daftar Kunjungan Terbaru
                  </h2>
                  <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider font-semibold">
                    Data real-time tamu hari ini
                  </p>
                </div>
                <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                  <!-- Search Bar -->
                  <div class="relative flex-grow md:flex-grow-0">
                    <span
                      class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined text-lg">search</span>
                    <input v-model="searchQuery" type="text"
                      class="w-full md:w-72 pl-12 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500/20 outline-none transition-all dark:text-white"
                      placeholder="Cari nama atau instansi..." />
                  </div>
                  <div
                    class="flex items-center gap-2 px-4 py-2 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-300 rounded-xl text-xs font-bold uppercase tracking-tight">
                    <span class="relative flex h-2.5 w-2.5">
                      <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                    </span>
                    Live Records
                  </div>
                </div>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-slate-50/80 dark:bg-slate-900/50">
                    <th
                      class="px-8 py-5 text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                      No</th>
                    <th
                      class="px-8 py-5 text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                      Informasi Tamu</th>
                    <th
                      class="px-8 py-5 text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                      Keperluan & Tujuan</th>
                    <th
                      class="px-8 py-5 text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                      Waktu Kedatangan</th>
                    <th
                      class="px-8 py-5 text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                      Status</th>
                    <th
                      class="px-8 py-5 text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 text-center">
                      Tindakan</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                  <tr v-for="(visitor, index) in filteredVisitors()" :key="visitor.id"
                    class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                    <td class="px-8 py-6 text-sm font-bold text-slate-400">{{ index + 1 }}</td>
                    <td class="px-8 py-6">
                      <div class="flex items-center gap-3">
                        <div>
                          <p class="text-base font-bold text-slate-900 dark:text-white">{{ visitor.nama }}</p>
                          <p
                            class="text-[11px] font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md mt-1 inline-block uppercase tracking-wider">
                            <i class="fa fa-building me-1 opacity-50"></i>{{ visitor.instansi }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-8 py-6">
                      <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ visitor.keperluan }}</p>
                      <p
                        class="text-[11px] text-slate-400 flex items-center gap-1.5 mt-1 font-semibold uppercase tracking-tight">
                        <span class="material-symbols-outlined text-[14px]">person</span>
                        Menemui: {{ visitor.tujuan_staf }}
                      </p>
                    </td>
                    <td class="px-8 py-6">
                      <p class="text-sm font-bold text-slate-800 dark:text-slate-200"><i class="fa fa-clock me-2 text-primary-500"></i>{{ visitor.waktu }}</p>
                    </td>
                    <td class="px-8 py-6">
                      <span
                        :class="['px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest leading-none border',
                          visitor.status === 'selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/40 dark:text-emerald-400 dark:border-emerald-800' : 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-900/40 dark:text-amber-400 dark:border-amber-800']">
                        <i :class="['fa me-1', visitor.status === 'selesai' ? 'fa-check-circle' : 'fa-clock']"></i>
                        {{ visitor.status === 'selesai' ? 'Sudah Selesai' : 'Sedang Berkunjung' }}
                      </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                      <div v-if="visitor.status === 'aktif'" class="flex justify-center">
                        <button @click="confirmCheckout(visitor.id, visitor.nama)"
                          class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[11px] font-black transition-all flex items-center gap-2 shadow-lg shadow-emerald-200 dark:shadow-none hover:-translate-y-0.5 active:translate-y-0">
                          <span class="material-symbols-outlined text-sm">logout</span>
                          Selesai Berkunjung
                        </button>
                      </div>
                      <div v-else class="text-slate-300 dark:text-slate-700 flex justify-center">
                        <span class="material-symbols-outlined opacity-30">check_circle</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="filteredVisitors().length === 0">
                    <td colspan="6" class="px-8 py-16 text-center">
                      <div class="flex flex-col items-center justify-center opacity-40">
                        <span class="material-symbols-outlined text-6xl mb-4">group_off</span>
                        <h4 class="text-xl font-display font-bold">Belum Ada Tamu</h4>
                        <p class="text-sm max-w-xs mx-auto mt-2">Daftar kunjungan hari ini masih kosong. Data akan diperbarui otomatis saat tamu mendaftar.</p>
                      </div>
                    </td>
                  </tr>
              </table>
            </div>
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
