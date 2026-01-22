<script setup lang="ts">
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { ref, computed } from 'vue'

// Receptionist data
const receptionists = ref([
  {
    id: 1,
    name: 'Budi Santoso',
    username: 'budisantoso',
    email: 'budi@example.com',
    phone: '081234567890'
  },
  {
    id: 2,
    name: 'Siti Aminah',
    username: 'sitiaminah',
    email: 'siti@example.com',
    phone: '089876543210'
  },
  {
    id: 3,
    name: 'Ahmad Hidayat',
    username: 'ahmadhidayat',
    email: 'ahmad@example.com',
    phone: '082345678901'
  }
])

// Search functionality
const searchQuery = ref('')
const filteredReceptionists = computed(() => {
  if (!searchQuery.value) return receptionists.value
  const query = searchQuery.value.toLowerCase()
  return receptionists.value.filter(rep =>
    rep.name.toLowerCase().includes(query) ||
    rep.username.toLowerCase().includes(query) ||
    rep.email.toLowerCase().includes(query)
  )
})

// Modal state
const isModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedReceptionist = ref<{ id: number; name: string } | null>(null)

// New receptionist form
const newRep = ref({
  name: '',
  username: '',
  email: '',
  phone: '',
  password: ''
})

// Add receptionist
const addReceptionist = () => {
  receptionists.value.push({
    id: Date.now(),
    name: newRep.value.name,
    username: newRep.value.username,
    email: newRep.value.email,
    phone: newRep.value.phone
  })
  isModalOpen.value = false
  newRep.value = { name: '', username: '', email: '', phone: '', password: '' }
}

// Confirm delete
const confirmDelete = (id: number, name: string) => {
  selectedReceptionist.value = { id, name }
  isDeleteModalOpen.value = true
}

// Delete receptionist
const deleteReceptionist = () => {
  if (selectedReceptionist.value) {
    receptionists.value = receptionists.value.filter(
      rep => rep.id !== selectedReceptionist.value!.id
    )
  }
  isDeleteModalOpen.value = false
  selectedReceptionist.value = null
}
</script>

<template>
  <DashboardLayout role="admin">
    <!-- Header Card -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center mb-0">
        <div class="header-title">
          <h4 class="card-title mb-0">
            <i class="fa-solid fa-users me-2"></i>Daftar Resepsionis
          </h4>
        </div>
      </div>
    </div>

    <!-- Content Card -->
    <div class="row mt-4">
      <div class="col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
              <i class="fa-solid fa-user-shield me-2"></i>Daftar Resepsionis Aktif
            </h5>
            <router-link to="/admin/receptionists/add" class="btn btn-primary btn-sm">
              <i class="fa-solid fa-user-plus me-1"></i> Tambah Resepsionis
            </router-link>
          </div>

          <div class="card-body">
            <!-- Search Box -->
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text border-end-0">
                  <i class="fa-solid fa-magnifying-glass text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" v-model="searchQuery"
                  placeholder="Cari nama atau username..." />
              </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Lengkap</th>
                    <th width="20%">Username</th>
                    <th width="20%">Email</th>
                    <th width="15%">No. HP</th>
                    <th width="15%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(rep, index) in filteredReceptionists" :key="rep.id">
                    <td>{{ index + 1 }}</td>
                    <td class="fw-semibold">{{ rep.name }}</td>
                    <td>{{ rep.username }}</td>
                    <td>{{ rep.email }}</td>
                    <td>{{ rep.phone }}</td>
                    <td class="text-center">
                      <router-link :to="`/admin/receptionists/edit/${rep.id}`" class="btn btn-sm btn-warning me-1"
                        title="Edit">
                        <i class="fa-solid fa-edit"></i>
                      </router-link>
                      <button @click="confirmDelete(rep.id, rep.name)" class="btn btn-sm btn-danger" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  <tr v-if="filteredReceptionists.length === 0">
                    <td colspan="6" class="text-center text-muted py-4">
                      <i class="fa-solid fa-search fa-2x mb-2 d-block"></i>
                      Tidak ada data resepsionis ditemukan
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="isDeleteModalOpen" class="modal-backdrop fade show" @click="isDeleteModalOpen = false"></div>
    <div v-if="isDeleteModalOpen" class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fa-solid fa-exclamation-triangle text-warning me-2"></i>
              Konfirmasi Hapus
            </h5>
            <button type="button" class="btn-close" @click="isDeleteModalOpen = false"></button>
          </div>
          <div class="modal-body">
            <p class="mb-0">
              Apakah Anda yakin ingin menghapus resepsionis
              <strong>"{{ selectedReceptionist?.name }}"</strong>?
            </p>
            <p class="text-muted small mt-2 mb-0">
              Tindakan ini tidak dapat dibatalkan.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="isDeleteModalOpen = false">
              Batal
            </button>
            <button type="button" class="btn btn-danger" @click="deleteReceptionist">
              <i class="fa-solid fa-trash me-1"></i> Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Modal backdrop fix */
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal {
  z-index: 1050;
}
</style>
