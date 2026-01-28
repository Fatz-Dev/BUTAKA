<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { useUsersStore } from '../../stores/users'
import Swal from 'sweetalert2'

// Users store
const usersStore = useUsersStore()
const receptionists = computed(() => usersStore.getReceptionists())

// Search functionality
const searchQuery = ref('')
const filteredReceptionists = computed(() => {
  if (!searchQuery.value) return receptionists.value
  const query = searchQuery.value.toLowerCase()
  return receptionists.value.filter(rep =>
    (rep.name && rep.name.toLowerCase().includes(query)) ||
    (rep.username && rep.username.toLowerCase().includes(query)) ||
    (rep.email && rep.email.toLowerCase().includes(query))
  )
})

// Confirm delete with SweetAlert
const confirmDelete = async (id: number, nama: string) => {
  const result = await Swal.fire({
    title: 'Hapus Resepsionis?',
    html: `<p>Anda yakin ingin menghapus <b>${nama}</b>?</p>`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6b7280',
    confirmButtonText: '<i class="fa fa-trash me-2"></i> Ya, Hapus',
    cancelButtonText: 'Batal'
  })

  if (result.isConfirmed) {
    const deleteResult = await usersStore.deleteUser(id)
    if (deleteResult.success) {
      Swal.fire({
        title: 'Berhasil!',
        text: `${nama} telah dihapus.`,
        icon: 'success',
        confirmButtonColor: '#3a57e8',
        timer: 2000,
        timerProgressBar: true
      })
    } else {
      Swal.fire({
        title: 'Gagal!',
        text: deleteResult.message || 'Terjadi kesalahan saat menghapus.',
        icon: 'error',
        confirmButtonColor: '#3a57e8'
      })
    }
  }
}

// Fetch users on mount
onMounted(async () => {
  await usersStore.fetchUsers()
})

// Edit functionality
const showEditModal = ref(false)
const isSubmitting = ref(false)
const editForm = ref({
  id: 0,
  name: '',
  email: '',
  password: '', // Optional
  is_active: 1
})

const openEditModal = (rep: any) => {
  editForm.value = {
    id: rep.id,
    name: rep.name,
    email: rep.email,
    password: '',
    is_active: rep.is_active ? 1 : 0
  }
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editForm.value = {
    id: 0,
    name: '',
    email: '',
    password: '',
    is_active: 1
  }
}

const handleUpdate = async () => {
  isSubmitting.value = true

  // Clean up data
  const updateData: any = {
    name: editForm.value.name,
    email: editForm.value.email,
    is_active: editForm.value.is_active
  }

  // Only send password if filled
  if (editForm.value.password) {
    updateData.password = editForm.value.password
  }

  const result = await usersStore.updateUser(editForm.value.id, updateData)
  isSubmitting.value = false

  if (result.success) {
    // Show success
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: 'Data resepsionis berhasil diperbarui',
      timer: 1500,
      showConfirmButton: false
    })
    closeEditModal()
    await usersStore.fetchUsers() // Refresh data
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: result.message || 'Gagal memperbarui data'
    })
  }
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

            <!-- Loading State -->
            <div v-if="usersStore.loading" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="text-muted mt-2">Memuat data...</p>
            </div>

            <!-- Table -->
            <div v-else class="table-responsive">
              <table class="table table-striped table-hover mb-0">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama</th>
                    <th width="25%">Email</th>
                    <th width="15%">Role</th>
                    <th width="15%" class="text-center">Status</th>
                    <th width="15%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(rep, index) in filteredReceptionists" :key="rep.id">
                    <td>{{ index + 1 }}</td>
                    <td class="fw-semibold">{{ rep.name }}</td>
                    <td>{{ rep.email }}</td>
                    <td>
                      <span class="badge rounded-pill" :class="rep.role === 'admin' ? 'bg-primary' : 'bg-info'">
                        {{ rep.role }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge rounded-pill" :class="rep.is_active ? 'bg-success' : 'bg-danger'">
                        {{ rep.is_active ? 'Aktif' : 'Non-Aktif' }}
                      </span>
                    </td>
                    <td class="text-center">
                      <button @click="openEditModal(rep)" class="btn btn-sm btn-warning me-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square text-white"></i>
                      </button>
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


    <!-- Edit Modal (Simple Overlay) -->
    <div v-if="showEditModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content dark:bg-[#222738] dark:text-white">
          <div class="modal-header dark:border-gray-700">
            <h5 class="modal-title">Edit Resepsionis</h5>
            <button type="button" class="btn-close dark:invert" @click="closeEditModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="handleUpdate">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label dark:text-gray-300">Nama Lengkap</label>
                  <input v-model="editForm.name" type="text"
                    class="form-control dark:bg-[#151824] dark:border-[#30384f] dark:text-white" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label dark:text-gray-300">Email</label>
                  <input v-model="editForm.email" type="email"
                    class="form-control dark:bg-[#151824] dark:border-[#30384f] dark:text-white" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label dark:text-gray-300">Password </label>
                  <input v-model="editForm.password" type="password"
                    class="form-control dark:bg-[#151824] dark:border-[#30384f] dark:text-white"
                    placeholder="Kosongkan jika tidak ingin mengubah">
                  <small class="text-muted dark:text-gray-400">Min. 6 karakter</small>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label dark:text-gray-300">Status</label>
                  <select v-model="editForm.is_active"
                    class="form-select dark:bg-[#151824] dark:border-[#30384f] dark:text-white">
                    <option :value="1">Aktif</option>
                    <option :value="0">Non-Aktif</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer px-0 pb-0 dark:border-gray-700">
                <button type="button" class="btn btn-secondary" @click="closeEditModal">Batal</button>
                <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                  <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-1"></span>
                  Simpan Perubahan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* No additional styles needed since we use SweetAlert instead of modal */
</style>
