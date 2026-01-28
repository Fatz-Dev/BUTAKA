<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useGuestLogsStore } from '../../stores/guestLogs'
import { useAuthStore } from '../../stores/auth'
import Swal from 'sweetalert2'

const guestLogsStore = useGuestLogsStore()
const authStore = useAuthStore()
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

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}

const confirmCheckout = async (id: number, name: string) => {
  const result = await Swal.fire({
    title: 'Check-out Tamu?',
    html: `<p class="text-gray-600"><b>${name}</b> akan ditandai telah selesai berkunjung.</p>`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#f59e0b',
    cancelButtonColor: '#6b7280',
    confirmButtonText: '<i class="fa fa-sign-out-alt me-2"></i> Ya, Check-out',
    cancelButtonText: 'Batal',
    customClass: {
      popup: 'rounded-xl',
      confirmButton: 'rounded-lg font-bold',
      cancelButton: 'rounded-lg font-bold'
    }
  })

  if (result.isConfirmed) {
    const checkoutResult = await guestLogsStore.checkoutGuest(id)
    if (checkoutResult.success) {
      Swal.fire({
        title: 'Berhasil!',
        text: `${name} telah berhasil check-out.`,
        icon: 'success',
        confirmButtonColor: '#3a57e8',
        confirmButtonText: 'OK',
        timer: 2000,
        timerProgressBar: true
      })
    } else {
      Swal.fire({
        title: 'Gagal!',
        text: checkoutResult.message || 'Terjadi kesalahan saat checkout.',
        icon: 'error',
        confirmButtonColor: '#3a57e8'
      })
    }
  }
}

onMounted(async () => {
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDarkMode.value = true
    document.documentElement.classList.add('dark')
  }

  // Fetch visitors from API
  await guestLogsStore.fetchVisitors()
})

const filteredVisitors = computed(() => {
  let logs = guestLogsStore.logs
  if (!searchQuery.value) return logs
  const query = searchQuery.value.toLowerCase()
  return logs.filter(v =>
    v.name.toLowerCase().includes(query) ||
    (v.institution && v.institution.toLowerCase().includes(query)) ||
    (v.instansi && v.instansi.toLowerCase().includes(query))
  )
})

const formatDate = (dateString: string | null | undefined) => {
  if (!dateString) return '-'
  try {
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      hour12: false
    }).format(date)
  } catch (e) {
    return dateString
  }
}
</script>

<template>
  <div
    class="min-h-screen bg-[#eff6ff] dark:bg-[#151824] font-body text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
      <!-- Navigation -->
      <header
        class="sticky top-0 z-50 bg-[#3a57e8] dark:bg-[#222738] text-white px-6 md:px-12 py-4 shadow-md transition-colors duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="bg-white p-1 rounded-full">
              <img src="/assets/images/logo-new.png" alt="Logo" class="h-10 w-10 object-contain" />
            </div>
            <div>
              <span class="text-xl font-display font-extrabold tracking-tight text-white">
                BuTaKa
              </span>
              <p class="text-xs text-blue-100 font-medium">Buku Tamu Kantor</p>
            </div>
          </div>
          <div class="flex items-center gap-4">
            <button @click="logout" class="text-sm font-bold text-white/80 hover:text-white transition-colors">
              Logout
            </button>
            <button @click="toggleTheme"
              class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all active:scale-90">
              <span v-if="!isDarkMode" class="material-symbols-outlined">dark_mode</span>
              <span v-else class="material-symbols-outlined">light_mode</span>
            </button>
          </div>
        </div>
      </header>

      <main class="flex-1 flex flex-col items-center py-12 px-6 md:px-12">
        <div class="w-full max-w-6xl space-y-8">
          <div
            class="w-full bg-white dark:!bg-[#222738] rounded-2xl shadow-lg border border-slate-100 dark:border-[#30384f] transition-colors duration-300 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-100 dark:border-[#30384f]">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                  <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                    Daftar Kunjungan Terbaru
                  </h2>
                  <p class="text-xs text-[#3a57e8] dark:text-blue-400 mt-1 uppercase tracking-wider font-bold">
                    DATA REAL-TIME TAMU HARI INI
                  </p>
                </div>
                <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                  <!-- Search Bar -->
                  <div class="relative flex-grow md:flex-grow-0">
                    <span
                      class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined text-lg">search</span>
                    <input v-model="searchQuery" type="text"
                      class="w-full md:w-64 pl-10 pr-4 py-2.5 bg-white dark:!bg-[#151824] border border-slate-200 dark:border-[#30384f] rounded-lg text-sm focus:ring-2 focus:ring-[#3a57e8] focus:border-[#3a57e8] outline-none transition-all dark:text-white dark:placeholder-slate-400"
                      placeholder="Cari nama atau instansi..." />
                  </div>
                  <div
                    class="flex items-center gap-2 px-4 py-2 bg-[#3a57e8] dark:bg-[#3a57e8]/90 text-white rounded-lg text-xs font-bold shadow-sm dark:shadow-[#3a57e8]/20">
                    <span class="relative flex h-2 w-2">
                      <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    LIVE RECORDS
                  </div>
                </div>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead>
                  <tr class="border-b border-slate-100 dark:border-[#30384f]">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                      No</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                      Nama Tamu</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                      Keperluan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                      Waktu Masuk</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                      Status</th>
                    <th
                      class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">
                      Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-[#30384f]">
                  <tr v-for="(visitor, index) in filteredVisitors" :key="visitor.id"
                    class="hover:bg-slate-50/50 dark:hover:bg-[#2a3352]/50 transition-colors">
                    <td class="px-6 py-5 text-sm font-semibold text-slate-500 dark:text-slate-400">{{ String(index +
                      1).padStart(2, '0') }}
                    </td>
                    <td class="px-6 py-5">
                      <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ visitor.name }}</p>
                        <span
                          class="inline-block mt-1 px-2 py-0.5 bg-amber-100 dark:bg-[#5a4f3d] text-amber-700 dark:text-amber-300 text-[10px] font-bold rounded uppercase">
                          {{ visitor.institution || visitor.instansi || 'Individu' }}
                        </span>
                      </div>
                    </td>
                    <td class="px-6 py-5">
                      <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ visitor.purpose }}</p>
                      <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                        <i class="fa fa-user me-1"></i> Menemui: {{ visitor.host_name }}
                      </p>
                    </td>
                    <td class="px-6 py-5">
                      <p class="text-sm font-bold text-[#3a57e8] dark:text-blue-400">{{ formatDate(visitor.check_in_time
                        || visitor.check_in_time ) }}</p>
                      <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{
                        visitor.check_in_time.split(',')[1] || ''
                      }}</p>
                    </td>
                    <td class="px-6 py-5">
                      <span :class="['inline-flex items-center px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-wide',
                        visitor.status === 'selesai'
                          ? 'bg-emerald-100 text-emerald-700 dark:bg-[#3d5a47] dark:text-emerald-300'
                          : 'bg-amber-100 text-amber-700 dark:bg-[#5a4f3d] dark:text-amber-300']">
                        {{ visitor.status === 'selesai' ? 'SELESAI' : 'MENUNGGU' }}
                      </span>
                    </td>
                    <td class="px-6 py-5 text-center">
                      <div v-if="visitor.status !== 'selesai'" class="flex justify-center">
                        <button @click="confirmCheckout(visitor.id, visitor.name)"
                          class="px-4 py-2 bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-500 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-2 shadow-sm dark:shadow-amber-900/30">
                          <i class="fa fa-sign-out-alt"></i>
                          Check-out
                        </button>
                      </div>
                      <div v-else class="flex justify-center">
                        <span class="text-slate-300 dark:text-slate-600 text-xs">—</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="filteredVisitors.length === 0">
                    <td colspan="6" class="px-6 py-16 text-center">
                      <div class="flex flex-col items-center justify-center">
                        <span
                          class="material-symbols-outlined text-5xl mb-3 text-slate-300 dark:text-slate-600">group_off</span>
                        <h4 class="text-lg font-bold text-slate-400 dark:text-slate-500">Belum Ada Tamu</h4>
                        <p class="text-sm text-slate-400 dark:text-slate-500 max-w-xs mx-auto mt-1">Daftar kunjungan
                          hari ini masih kosong.
                        </p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>

      <footer class="py-8 text-center transition-colors duration-300">
        <p class="text-slate-400 dark:text-slate-500 text-sm">
          © 2025 BuTaKa. All rights reserved.
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
