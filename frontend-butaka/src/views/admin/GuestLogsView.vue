<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { useGuestLogsStore } from '../../stores/guestLogs'

const guestLogsStore = useGuestLogsStore()
const searchQuery = ref('')
const selectedDate = ref('')

const filteredLogs = computed(() => {
  return guestLogsStore.logs.filter(log => {
    // Search filter
    const matchesSearch = !searchQuery.value ||
      log.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      log.institution.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      log.purpose.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      log.host_name.toLowerCase().includes(searchQuery.value.toLowerCase())

    // Date filter
    const matchesDate = !selectedDate.value ||
      (log.check_in_time && log.check_in_time.startsWith(selectedDate.value))

    return matchesSearch && matchesDate
  })
})

onMounted(async () => {
  await guestLogsStore.fetchVisitors()
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
    return (dateString as string) || '-'
  }
}
</script>

<template>
  <DashboardLayout role="admin">
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm">
          <div
            class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3 gap-3">
            <h5 class="mb-0">
              <i class="fa-solid fa-users me-2"></i>Daftar Riwayat Kunjungan Tamu
            </h5>
            <div class="d-flex flex-column flex-sm-row gap-2">
              <div class="input-group input-group-sm" style="min-width: 250px;">
                <span class="input-group-text bg-transparent border-end-0 text-muted ps-3">
                  <i class="fa fa-search"></i>
                </span>
                <input v-model="searchQuery" type="text" class="form-control border-start-0 shadow-none ps-2"
                  placeholder="Cari nama, instansi..." />
              </div>
              <input v-model="selectedDate" type="date" class="form-control form-control-sm" style="width: auto;" />
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <div v-if="guestLogsStore.loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <table v-else id="datatable" class="table table-hover table-striped dataTable" width="100%"
                cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama Tamu</th>
                    <th width="15%">Instansi</th>
                    <th width="35%">Tujuan & Keperluan</th>
                    <th width="15%">Waktu Masuk</th>
                    <th width="10%">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(log, index) in filteredLogs" :key="log.id">
                    <td>{{ index + 1 }}</td>
                    <td><span class="fw-medium">{{ log.name }}</span></td>
                    <td>{{ log.institution }}</td>
                    <td>
                      <div class="text-wrap">{{ log.purpose }}</div>
                      <small class="text-muted"><i class="fa fa-user-tie me-1"></i>{{ log.host_name }}</small>
                    </td>
                    <td><small class="fw-bold">{{ formatDate(log.check_in_time) }}</small></td>
                    <td>
                      <span class="badge rounded-pill"
                        :class="log.status === 'selesai' ? 'bg-soft-success text-success border border-success' : 'bg-soft-warning text-warning border border-warning'">
                        {{ log.status === 'selesai' ? 'Selesai' : 'Aktif' }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="filteredLogs.length === 0">
                    <td colspan="6" class="text-center py-5">
                      <div class="mb-3">
                        <i class="fa fa-folder-open text-muted" style="font-size: 40px; opacity: 0.3;"></i>
                      </div>
                      <h6 class="text-muted">{{ searchQuery || selectedDate ? 'Tidak ada data yang cocok dengan filter'
                        : 'Riwayat kunjungan masih kosong' }}</h6>
                      <p class="text-muted small mb-0">{{ searchQuery || selectedDate ? 'Coba ubah kata kunci atau pilih tanggal lain' : 'Semua data tamu yang mendaftar akan tersimpan di sini.' }}</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
.table-responsive {
  overflow-x: auto;
}

.badge {
  font-size: 0.75rem;
  padding: 0.4em 0.8em;
}
</style>
