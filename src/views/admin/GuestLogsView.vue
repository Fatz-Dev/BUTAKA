<script setup lang="ts">
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { useGuestLogsStore } from '../../stores/guestLogs'

const guestLogsStore = useGuestLogsStore()

</script>

<template>
  <DashboardLayout role="admin">
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">
              <i class="fa-solid fa-users me-2"></i>Daftar Riwayat Kunjungan Tamu
            </h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="datatable" class="table table-hover table-striped dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama Tamu</th>
                    <th width="15%">Asal/Instansi</th>
                    <th width="35%">Tujuan & Keperluan</th>
                    <th width="15%">Waktu Masuk</th>
                    <th width="10%">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(log, index) in guestLogsStore.logs" :key="log.id">
                    <td>{{ index + 1 }}</td>
                    <td><span class="fw-medium text-primary">{{ log.nama }}</span></td>
                    <td>{{ log.instansi }}</td>
                    <td>
                      <div class="text-wrap">{{ log.keperluan }}</div>
                      <small class="text-muted"><i class="fa fa-user-tie me-1"></i>{{ log.tujuan_staf }}</small>
                    </td>
                    <td><small class="fw-bold text-dark">{{ log.waktu }}</small></td>
                    <td>
                      <span class="badge rounded-pill"
                        :class="log.status === 'selesai' ? 'bg-soft-success text-success border border-success' : 'bg-soft-warning text-warning border border-warning'">
                        {{ log.status === 'selesai' ? 'Selesai' : 'Aktif' }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="guestLogsStore.logs.length === 0">
                    <td colspan="6" class="text-center py-5">
                      <div class="mb-3">
                        <i class="fa fa-folder-open text-muted" style="font-size: 40px; opacity: 0.3;"></i>
                      </div>
                      <h6 class="text-muted">Riwayat kunjungan masih kosong</h6>
                      <p class="text-muted small mb-0">Semua data tamu yang mendaftar akan tersimpan di sini.</p>
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
