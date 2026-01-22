<script setup lang="ts">
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
    nama: '',
    username: '',
    email: '',
    password: '',
    no_hp: ''
})

const showPassword = ref(false)

const handleSave = () => {
    console.log('Saving receptionist:', form.value)
    // Logic to save would go here
    alert('Resepsionis berhasil disimpan!')
    router.push('/admin/receptionists')
}

const handleReset = () => {
    form.value = {
        nama: '',
        username: '',
        email: '',
        password: '',
        no_hp: ''
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
                                        <label for="nama" class="form-label fw-semibold">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="nama" v-model="form.nama"
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="no_hp" class="form-label fw-semibold">No. WhatsApp/HP</label>
                                        <input type="text" class="form-control" id="no_hp" v-model="form.no_hp"
                                            placeholder="08xxxxxxxx" />
                                    </div>
                                </div>

                                <hr class="my-4" />

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-save me-2"></i>Simpan Resepsionis
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
