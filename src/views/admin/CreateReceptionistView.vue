<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { useUsersStore } from '../../stores/users'
import Swal from 'sweetalert2'

const router = useRouter()
const usersStore = useUsersStore()
const isSubmitting = ref(false)

const form = ref({
    name: '',
    username: '',
    email: '',
    password: '',
    confirmPassword: '',
    phone: ''
})

const showPassword = ref(false)

const handleSave = async () => {
    // Basic frontend validation
    if (form.value.password !== form.value.confirmPassword) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Konfirmasi password tidak cocok!',
            confirmButtonColor: '#3a57e8'
        })
        return
    }

    isSubmitting.value = true
    const result = await usersStore.addUser({
        name: form.value.name,
        username: form.value.username,
        email: form.value.email,
        phone: form.value.phone,
        password: form.value.password,
        password_confirmation: form.value.confirmPassword, // Send confirmation to API
        role: 'resepsionis'
    })
    isSubmitting.value = false

    if (result.success) {
        await Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Resepsionis baru telah berhasil ditambahkan.',
            confirmButtonColor: '#3a57e8',
            timer: 2000,
            timerProgressBar: true
        })
        router.push('/admin/receptionists')
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: result.message || 'Terjadi kesalahan saat menambahkan resepsionis.',
            confirmButtonColor: '#3a57e8'
        })
    }
}

const handleReset = () => {
    form.value = {
        name: '',
        username: '',
        email: '',
        password: '',
        confirmPassword: '',
        phone: ''
    }
}
</script>

<template>
    <DashboardLayout role="admin">
        <!-- Header -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center mb-0">
                <div class="header-title">
                    <h4 class="card-title">
                        <i class="fa-solid fa-user-plus me-2"></i>Tambah Resepsionis Baru
                    </h4>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="container-fluid mt-4 px-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form @submit.prevent="handleSave" id="formResepsionis">
                                <h5 class="mb-3">
                                    <i class="fa-solid fa-user-shield me-2"></i>Informasi Akun & Profil
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="name" v-model="form.name"
                                            placeholder="Masukkan nama lengkap resepsionis" required />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label fw-semibold">
                                            Username <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="username" v-model="form.username"
                                            placeholder="Masukkan username untuk login" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                        <input type="email" class="form-control" id="email" v-model="form.email"
                                            placeholder="contoh@email.com" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label fw-semibold">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input :type="showPassword ? 'text' : 'password'" class="form-control"
                                                id="password" v-model="form.password"
                                                placeholder="Masukkan password minimal 6 karakter" required />
                                            <button class="btn btn-outline-secondary" type="button"
                                                @click="showPassword = !showPassword">
                                                <i
                                                    :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirmPassword" class="form-label fw-semibold">
                                            Konfirmasi Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input :type="showPassword ? 'text' : 'password'" class="form-control"
                                                id="confirmPassword" v-model="form.confirmPassword"
                                                placeholder="Masukkan ulang password" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-semibold">No. WhatsApp/HP</label>
                                        <input type="text" class="form-control" id="phone" v-model="form.phone"
                                            placeholder="08xxxxxxxx" />
                                    </div>
                                </div>

                                <hr class="my-4" />

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                                        <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
                                        <i v-else class="fa-solid fa-save me-2"></i>{{ isSubmitting ? 'Menyimpan...' :
                                            'Simpan Resepsionis' }}
                                    </button>
                                    <button type="button" @click="handleReset" class="btn btn-secondary">
                                        <i class="fa-solid fa-redo me-2"></i>Reset Form
                                    </button>
                                    <router-link to="/admin/receptionists" class="btn btn-danger">
                                        <i class="fa-solid fa-times me-2"></i>Batal
                                    </router-link>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
/* Additional component-specific styles if needed */
</style>
