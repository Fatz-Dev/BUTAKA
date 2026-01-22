<script setup lang="ts">
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { ref, computed, onMounted } from 'vue'
import { useGuestLogsStore } from '../../stores/guestLogs'

const guestLogsStore = useGuestLogsStore()

const stats = computed(() => {
  const allLogs = guestLogsStore.logs
  const logsToday = allLogs.filter(() => {
    // Basic date matching for today (waktu format is "HH:mm, DD MMM YYYY" or similar)
    // For now we count all as it's a demo
    return true
  })

  const activeCount = allLogs.filter(l => l.status === 'aktif').length
  const totalLogs = allLogs.length

  return [
    { title: 'Tamu Hari Ini', value: logsToday.length, icon: 'fa fa-users', colorClass: 'circle-progress-primary' },
    { title: 'Tamu Aktif', value: activeCount, icon: 'fa fa-user-check', colorClass: 'circle-progress-success' },
    { title: 'Total Kunjungan', value: totalLogs, icon: 'fa fa-history', colorClass: 'circle-progress-info' },
    { title: 'Rata-rata Rating', value: 4.8, icon: 'fa fa-star', colorClass: 'circle-progress-warning' }
  ]
})

// System summary data
const systemStatus = ref([
  { label: 'Status Server', value: 'Normal', status: 'success' },
  { label: 'Koneksi Database', value: 'Connected', status: 'primary' },
  { label: 'Lapisan Keamanan', value: 'Active', status: 'info' }
])

const recentVisitors = computed(() => {
  return guestLogsStore.logs.slice(0, 5).map(l => ({
    name: l.nama,
    instansi: l.instansi,
    menemui: l.tujuan_staf,
    keperluan: l.keperluan,
    waktu: l.waktu,
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

onMounted(() => {
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
      <div v-for="(stat, index) in stats" :key="stat.title" class="col-md-6 col-lg-3">
        <div class="card card-slide" data-aos="fade-up" :data-aos-delay="(index + 1) * 100">
          <div class="card-body">
            <div class="progress-widget">
              <div class="text-center circle-progress-01 circle-progress mb-3" :class="stat.colorClass"
                style="width: 80px; height: 80px; margin: 0 auto">
                <i :class="stat.icon" style="font-size: 32px; line-height: 80px"></i>
              </div>
              <div class="progress-detail text-center">
                <p class="mb-2 text-muted">{{ stat.title }}</p>
                <h3 class="counter mb-0">{{ stat.value }}</h3>
              </div>
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
          <div class="card-body">
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
                  <tr v-for="(visitor, index) in recentVisitors" :key="visitor.name + visitor.waktu">
                    <td>{{ index + 1 }}</td>
                    <td>
                      <span class="fw-medium text-primary">{{ visitor.name }}</span>
                    </td>
                    <td>{{ visitor.instansi }}</td>
                    <td><small class="text-dark"><i class="fa fa-user-tie me-1"></i>{{ visitor.menemui }}</small></td>
                    <td>
                      <div class="text-wrap">{{ visitor.keperluan }}</div>
                    </td>
                    <td><small class="fw-bold text-dark">{{ visitor.waktu.split(',')[0] }}</small></td>
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
