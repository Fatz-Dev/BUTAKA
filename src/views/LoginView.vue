<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Swal from 'sweetalert2'

const router = useRouter()
const authStore = useAuthStore()
const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const isLoading = ref(false)

const handleLogin = async () => {
  if (!email.value || !password.value) {
    Swal.fire({
      icon: 'warning',
      title: 'Peringatan',
      text: 'Email dan password harus diisi!',
      confirmButtonColor: '#3a57e8'
    })
    return
  }

  isLoading.value = true

  const result = await authStore.login(email.value, password.value)

  isLoading.value = false

  if (result.success && result.user) {
    // Debug: log role
    console.log('Login successful! User role:', result.user.role)

    // Determine redirect path based on role
    const userRole = result.user.role?.toLowerCase()
    const redirectPath = userRole === 'admin' ? '/admin' : '/receptionist'

    console.log('Will redirect to:', redirectPath)

    // Show success message
    await Swal.fire({
      icon: 'success',
      title: 'Login Berhasil!',
      text: `Selamat datang, ${result.user.name || email.value}!`,
      timer: 1500,
      timerProgressBar: true,
      showConfirmButton: false
    })

    // Redirect after Swal closes - use replace to prevent back button issues
    console.log('Now redirecting...')
    await router.replace(redirectPath)
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Login Gagal',
      text: result.message || 'Email atau password salah!',
      confirmButtonColor: '#3a57e8'
    })
  }
}
</script>

<template>
  <div class="wrapper">
    <section class="login-content">
      <div class="row m-0 align-items-center bg-white vh-100">
        <div class="col-md-6">
          <div class="row justify-content-center">
            <div class="col-md-10">
              <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                <div class="card-body">
                  <router-link to="/" class="navbar-brand d-flex align-items-center mb-3">
                    <!--Logo start-->
                    <div class="logo-main">
                      <div class="logo-normal">
                        <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                            transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                          <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                            transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                          <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                            transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                          <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                            transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                        </svg>
                      </div>
                      <div class="logo-mini">
                        <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                            transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                          <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                            transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                          <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                            transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                          <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                            transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                        </svg>
                      </div>
                    </div>
                    <!--logo End-->
                    <h4 class="logo-title ms-3">BuTaKa</h4>
                  </router-link>
                  <h2 class="mb-2 text-center">Sign In</h2>
                  <p class="text-center">Login to stay connected.</p>

                  <form @submit.prevent="handleLogin">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" v-model="email" class="form-control" id="email"
                            placeholder="admin@example.com" required :disabled="isLoading" />
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="password" class="form-label">Password</label>
                          <input type="password" v-model="password" class="form-control" id="password"
                            placeholder="Enter your password" required :disabled="isLoading" />
                        </div>
                      </div>
                      <div class="col-lg-12 d-flex justify-content-between">
                        <div class="form-check mb-3">
                          <input type="checkbox" v-model="rememberMe" class="form-check-input" id="customCheck1" />
                          <label class="form-check-label" for="customCheck1">Remember Me</label>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex justify-content-center">
                      <button type="submit" class="btn btn-primary" :disabled="isLoading">
                        <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                        {{ isLoading ? 'Loading...' : 'Sign In' }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="sign-bg">
            <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g opacity="0.05">
                <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857"
                  transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF" />
                <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857"
                  transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF" />
                <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857"
                  transform="rotate(45 61.9355 138.545)" fill="#3B8AFF" />
                <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857"
                  transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF" />
              </g>
            </svg>
          </div>
        </div>

        <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
          <img src="/assets/images/auth/01.png" class="img-fluid gradient-main animated-scaleX" alt="images">
          <img src="/assets/images/loader.gif" alt="User-Profile"
            class="img-fluid avatar avatar-100 animated-scaleX avatar-rounded position-absolute top-50 start-50 translate-middle">
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Scoped styles to ensure the layout matches the template exactly */
.login-content {
  overflow: hidden;
}

.sign-bg {
  position: absolute;
  left: 0;
  top: 0;
  z-index: -1;
}

.navbar-brand .logo-title {
  color: var(--bs-primary);
  font-weight: 700;
}
</style>
