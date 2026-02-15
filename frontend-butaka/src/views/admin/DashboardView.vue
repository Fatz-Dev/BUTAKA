<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { useGuestLogsStore } from '../../stores/guestLogs'
import VueApexCharts from 'vue3-apexcharts'
import type { ApexOptions } from 'apexcharts'

const guestLogsStore = useGuestLogsStore()

const stats = computed(() => {
  const allLogs = guestLogsStore.logs
  const activeCount = allLogs.filter(l => ['berkunjung', 'menunggu'].includes(l.status)).length
  const totalLogs = allLogs.length

  // Mock data for trends (since we don't have historical API yet) - Random fluctuations
  const sparklineData = Array.from({ length: 10 }, () => Math.floor(Math.random() * (15 - 5 + 1) + 5))
  const barData = Array.from({ length: 7 }, () => Math.floor(Math.random() * (20 - 5 + 1) + 5))

  return {
    totalVisits: {
      value: totalLogs,
      series: [{ name: 'Visits', data: sparklineData }],
      chartOptions: {
        chart: { type: 'area', sparkline: { enabled: true } },
        stroke: { curve: 'smooth', width: 2 },
        fill: { opacity: 0.3 },
        colors: ['#1d4ed8'],
        tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } }
      } as ApexOptions
    },
    activeVisitors: {
      value: activeCount,
      series: [Math.min((activeCount / 50) * 100, 100)], // Assuming capacity ~50 for demo
      chartOptions: {
        chart: { type: 'radialBar', sparkline: { enabled: true } },
        plotOptions: {
          radialBar: {
            hollow: { size: '60%' },
            dataLabels: { show: false },
            track: { background: '#f1f5f9' }
          }
        },
        colors: ['#10b981'],
        stroke: { lineCap: 'round' }
      } as ApexOptions
    },
    todayVisits: {
      value: allLogs.filter(l => {
        const today = new Date().toISOString().split('T')[0]
        return l.check_in_time && l.check_in_time.startsWith(today)
      }).length, // Actual today count from store
      series: [{ name: 'Today', data: barData }],
      chartOptions: {
        chart: { type: 'bar', sparkline: { enabled: true } },
        plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
        colors: ['#0ea5e9'],
        tooltip: { fixed: { enabled: false }, x: { show: false } }
      } as ApexOptions
    },
    rating: {
      value: 4.8,
      series: [96], // 4.8 * 20
      chartOptions: {
        chart: { type: 'radialBar', sparkline: { enabled: true } },
        plotOptions: {
          radialBar: {
            hollow: { size: '60%' },
            dataLabels: { show: false },
            track: { background: '#f1f5f9' }
          }
        },
        colors: ['#f59e0b'],
        stroke: { lineCap: 'round' }
      } as ApexOptions
    }
  }
})

// System summary data
const systemStatus = ref([
  { label: 'Status Server', value: 'Normal', status: 'success' },
  { label: 'Koneksi Database', value: 'Connected', status: 'primary' },
  { label: 'Lapisan Keamanan', value: 'Active', status: 'info' }
])

const recentVisitors = computed(() => {
  return guestLogsStore.logs.slice(0, 5).map(l => ({
    name: l.name,
    institution: l.institution || l.instansi,
    host_name: l.host_name,
    purpose: l.purpose,
    check_in_time: l.check_in_time || l.created_at,
    status: l.status
  }))
})

// Current date
const currentDate = computed(() => {
  const options: Intl.DateTimeFormatOptions = {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }
  return new Date().toLocaleDateString('id-ID', options)
})

const formatDate = (dateString: string | undefined | null) => {
  if (!dateString) return ''
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

onMounted(async () => {
  // Fetch visitors from API
  await guestLogsStore.fetchVisitors()

  // Initialize AOS if available
  if (typeof window !== 'undefined' && (window as any).AOS) {
    (window as any).AOS.init()
  }
})
</script>

<template>
  <DashboardLayout role="admin">
    <!-- Dashboard Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title mb-1">Selamat Datang, Admin!</h5>
                <p class="text-muted mb-0">Monitoring kunjungan tamu kantor (BuTaKa) secara real-time di sini.</p>
              </div>
              <div class="text-end">
                <small class="text-muted">
                  <i class="fa fa-calendar me-1"></i>
                  {{ currentDate }}
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
      <!-- Total Visits (Sparkline) -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card card-slide w-100 h-40" data-aos="fade-up" data-aos-delay="100">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
              <div>
                <p class="mb-0 text-muted">Total Kunjungan</p>
                <h3 class="mb-0 counter">{{ stats.totalVisits.value }}</h3>
              </div>
              <div class="p-3 bg-soft-primary rounded-circle">
                <i class="fa fa-users text-primary"></i>
              </div>
            </div>
            <div class="mt-auto d-flex align-items-end" style="height: 120px;">
              <VueApexCharts type="area" height="60" width="100%" :options="stats.totalVisits.chartOptions"
                :series="stats.totalVisits.series"></VueApexCharts>
            </div>
          </div>
        </div>
      </div>

      <!-- Active Visitors (Radial Bar) -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card card-slide w-100 h-40" data-aos="fade-up" data-aos-delay="200">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
              <div>
                <p class="mb-0 text-muted">Tamu Aktif</p>
                <h3 class="mb-0 counter">{{ stats.activeVisitors.value }}</h3>
                <small class="text-success" style="font-size: 0.75rem;"><i class="fa fa-arrow-up me-1"></i>Sedang
                  berkunjung</small>
              </div>
              <div class="p-3 bg-soft-success rounded-circle">
                <i class="fa fa-user-clock text-success"></i>
              </div>
            </div>
            <div class="mt-auto d-flex justify-content-center align-items-center" style="height: 120px;">
              <VueApexCharts type="radialBar" height="150" :options="stats.activeVisitors.chartOptions"
                :series="stats.activeVisitors.series"></VueApexCharts>
            </div>
          </div>
        </div>
      </div>

      <!-- Today's Visits (Bar) -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card card-slide w-100 h-40" data-aos="fade-up" data-aos-delay="300">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
              <div>
                <p class="mb-0 text-muted">Kunjungan Hari Ini</p>
                <h3 class="mb-0 counter">{{ stats.todayVisits.value }}</h3>
              </div>
              <div class="p-3 bg-soft-info rounded-circle">
                <i class="fa fa-calendar-day text-info"></i>
              </div>
            </div>
            <div class="mt-auto d-flex align-items-end" style="height: 120px;">
              <VueApexCharts type="bar" height="60" width="100%" :options="stats.todayVisits.chartOptions"
                :series="stats.todayVisits.series"></VueApexCharts>
            </div>
          </div>
        </div>
      </div>

      <!-- Rating (Radial Bar) -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card card-slide w-100 h-40" data-aos="fade-up" data-aos-delay="400">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
              <div>
                <p class="mb-0 text-muted">Rata-rata Rating</p>
                <h3 class="mb-0 counter">{{ stats.rating.value }}</h3>
                <small class="text-warning" style="font-size: 0.75rem;"><i class="fa fa-star me-1"></i>dari 5.0</small>
              </div>
              <div class="p-3 bg-soft-warning rounded-circle">
                <i class="fa fa-star text-warning"></i>
              </div>
            </div>
            <div class="mt-auto d-flex justify-content-center align-items-center" style="height: 120px;">
              <VueApexCharts type="radialBar" height="150" :options="stats.rating.chartOptions"
                :series="stats.rating.series"></VueApexCharts>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action and Info Section -->
    <div class="row">
      <!-- Action Cards -->
      <div class="col-md-12 col-lg-6">
        <div class="card" data-aos="fade-up" data-aos-delay="500">
          <div class="card-header">
            <h4 class="card-title">Aksi Cepat</h4>
          </div>
          <div class="card-body text-center py-5">
            <p class="text-muted mb-4">
              Gunakan pintasan di bawah ini untuk mengelola data kunjungan dan feedback dari tamu kantor.
            </p>
            <div class="d-flex justify-content-center gap-3">
              <router-link to="/admin/logs" class="btn btn-primary">
                <i class="fa fa-users-cog me-2"></i>Lihat Daftar Kunjungan
              </router-link>
              <router-link to="/admin/feedback" class="btn btn-outline-info">
                <i class="fa fa-comment-alt me-2"></i>Data Feedback
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Detail -->
      <div class="col-md-12 col-lg-6">
        <div class="card" data-aos="fade-up" data-aos-delay="600">
          <div class="card-header d-flex justify-content-between">
            <div class="header-title">
              <h4 class="card-title">Ringkasan Sistem</h4>
            </div>
          </div>
          <div class="card-body mb-16">
            <ul class="list-group list-group-flush">
              <li v-for="item in systemStatus" :key="item.label"
                class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                {{ item.label }}
                <span class="badge rounded-pill" :class="`bg-${item.status}`">{{ item.value }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Visitors -->
    <div class="row">
      <div class="col-12">
        <div class="card" data-aos="fade-up" data-aos-delay="700">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div class="header-title">
              <h4 class="card-title">Kunjungan Terbaru Hari Ini</h4>
            </div>
            <router-link to="/admin/logs" class="btn btn-sm btn-primary">
              Lihat Semua <i class="fa fa-arrow-right ms-1"></i>
            </router-link>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama Tamu</th>
                    <th width="15%">Asal/Instansi</th>
                    <th width="20%">Menemui</th>
                    <th width="20%">Keperluan</th>
                    <th width="10%">Waktu</th>
                    <th width="10%">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(visitor, index) in recentVisitors" :key="visitor.name + visitor.check_in_time">
                    <td>{{ index + 1 }}</td>
                    <td>
                      <span class="fw-medium">{{ visitor.name }}</span>
                    </td>
                    <td>{{ visitor.institution }}</td>
                    <td><small class="text-muted"><i class="fa fa-user-tie me-1"></i>{{ visitor.host_name }}</small>
                    </td>
                    <td>
                      <div class="text-wrap">{{ visitor.purpose }}</div>
                    </td>
                    <td><small class="fw-bold">{{ formatDate(visitor.check_in_time) }}</small></td>
                    <td>
                      <span class="badge rounded-pill"
                        :class="visitor.status === 'aktif' ? 'bg-soft-warning text-warning border border-warning' : 'bg-soft-success text-success border border-success'">
                        {{ visitor.status === 'aktif' ? 'Aktif' : 'Selesai' }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="recentVisitors.length === 0">
                    <td colspan="7" class="text-center py-5">
                      <div class="mb-3">
                        <i class="fa fa-users-slash text-muted" style="font-size: 40px; opacity: 0.3;"></i>
                      </div>
                      <h6 class="text-muted">Belum ada kunjungan hari ini</h6>
                      <p class="text-muted small mb-0">Data akan muncul secara otomatis saat tamu mengisi buku tamu.</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Dashboard Content End-->
  </DashboardLayout>
</template>

<style scoped>
/* Using Hope UI styles from global CSS */
</style>
