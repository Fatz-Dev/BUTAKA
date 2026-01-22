<script setup lang="ts">
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { ref, computed, onMounted } from 'vue'

// Statistics data
const stats = ref([
  { title: 'Tamu Hari Ini', value: 12, icon: 'fa fa-users', colorClass: 'circle-progress-primary' },
  { title: 'Tamu Aktif', value: 5, icon: 'fa fa-user-check', colorClass: 'circle-progress-success' },
  { title: 'Total Kunjungan', value: 156, icon: 'fa fa-clipboard-list', colorClass: 'circle-progress-info' },
  { title: 'Check-out Hari Ini', value: 7, icon: 'fa fa-sign-out-alt', colorClass: 'circle-progress-warning' }
])

// Recent visitors data (untuk tampilan awal)
const recentVisitors = ref([
  {
    id: 1,
    name: 'John Doe',
    instansi: 'PT. Teknologi Maju',
    menemui: 'Budi Santoso',
    keperluan: 'Meeting Kerjasama',
    waktu_masuk: '09:00',
    status: 'aktif'
  },
  {
    id: 2,
    name: 'Jane Smith',
    instansi: 'Dinas Pendidikan',
    menemui: 'Siti Aminah',
    keperluan: 'Konsultasi',
    waktu_masuk: '08:30',
    status: 'selesai'
  },
  {
    id: 3,
    name: 'Ahmad Fauzi',
    instansi: 'Bank Mandiri',
    menemui: 'Joko Widodo',
    keperluan: 'Audit Keuangan',
    waktu_masuk: '08:00',
    status: 'selesai'
  },
  {
    id: 4,
    name: 'Rina Kusuma',
    instansi: 'Telkom Indonesia',
    menemui: 'Dewi Lestari',
    keperluan: 'Presentasi Produk',
    waktu_masuk: '07:45',
    status: 'aktif'
  }
])

// Current date
const currentDate = computed(() => {
  const options: Intl.DateTimeFormatOptions = { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric' 
  }
  return new Date().toLocaleDateString('id-ID', options)
})

// Handle checkout
const handleCheckout = (visitorId: number) => {
  const visitor = recentVisitors.value.find(v => v.id === visitorId)
  if (visitor) {
    visitor.status = 'selesai'
    alert(`${visitor.name} telah berhasil di-checkout`)
  }
}

onMounted(() => {
  // Initialize AOS if available
  if (typeof window !== 'undefined' && (window as any).AOS) {
    (window as any).AOS.init()
  }
})
</script>

<template>
  <DashboardLayout role="receptionist">
    <!-- Dashboard Content -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title mb-1">Selamat Datang, Resepsionis!</h5>
                <p class="text-muted mb-0">Kelola data kunjungan tamu hari ini</p>
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
      <div 
        v-for="(stat, index) in stats" 
        :key="stat.title"
        class="col-md-6 col-lg-3"
      >
        <div 
          class="card card-slide"
          data-aos="fade-up"
          :data-aos-delay="(index + 1) * 100"
        >
          <div class="card-body">
            <div class="progress-widget">
              <div 
                class="text-center circle-progress-01 circle-progress mb-3" 
                :class="stat.colorClass"
                style="width: 80px; height: 80px; margin: 0 auto"
              >
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

    <!-- Quick Actions -->
    <div class="row">
      <div class="col-12">
        <div class="card" data-aos="fade-up" data-aos-delay="500">
          <div class="card-header">
            <h4 class="card-title">Aksi Cepat</h4>
          </div>
          <div class="card-body text-center py-4">
            <p class="text-muted mb-4">
              Gunakan pintasan di bawah untuk mengelola tamu dengan lebih cepat
            </p>
            <div class="d-flex justify-content-center gap-3">
              <router-link to="/receptionist/new-guest" class="btn btn-primary">
                <i class="fa fa-user-plus me-2"></i>Daftarkan Tamu Baru
              </router-link>
              <router-link to="/receptionist/guests" class="btn btn-outline-info">
                <i class="fa fa-list me-2"></i>Lihat Semua Tamu
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Visitors Table -->
    <div class="row">
      <div class="col-12">
        <div class="card" data-aos="fade-up" data-aos-delay="600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div class="header-title">
              <h4 class="card-title">Kunjungan Hari Ini</h4>
            </div>
            <router-link to="/receptionist/guests" class="btn btn-sm btn-primary">
              Lihat Semua <i class="fa fa-arrow-right ms-1"></i>
            </router-link>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Instansi</th>
                    <th>Menemui</th>
                    <th>Keperluan</th>
                    <th>Waktu Masuk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="visitor in recentVisitors" :key="visitor.id">
                    <td>
                      <span class="fw-medium">{{ visitor.name }}</span>
                    </td>
                    <td>{{ visitor.instansi }}</td>
                    <td>{{ visitor.menemui }}</td>
                    <td>{{ visitor.keperluan }}</td>
                    <td>
                      <small class="text-muted">{{ visitor.waktu_masuk }}</small>
                    </td>
                    <td>
                      <span 
                        class="badge"
                        :class="visitor.status === 'aktif' ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary'"
                      >
                        {{ visitor.status === 'aktif' ? 'Aktif' : 'Selesai' }}
                      </span>
                    </td>
                    <td>
                      <button
                        v-if="visitor.status === 'aktif'"
                        @click="handleCheckout(visitor.id)"
                        class="btn btn-sm btn-outline-danger"
                      >
                        <i class="fa fa-sign-out-alt me-1"></i>Check-out
                      </button>
                      <span v-else class="text-muted">-</span>
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
