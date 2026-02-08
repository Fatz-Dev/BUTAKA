<script setup lang="ts">
import { computed, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const props = defineProps<{
  role: 'admin' | 'receptionist'
}>()

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

// Theme mode state
const currentTheme = ref<'auto' | 'dark' | 'light'>('light')

// Set theme function
const setTheme = (theme: 'auto' | 'dark' | 'light') => {
  currentTheme.value = theme
  localStorage.setItem('theme', theme)

  // Remove existing theme classes
  document.documentElement.removeAttribute('data-bs-theme')
  document.body.classList.remove('dark')

  if (theme === 'dark') {
    document.documentElement.setAttribute('data-bs-theme', 'dark')
    document.body.classList.add('dark')
  } else if (theme === 'auto') {
    // Check system preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    if (prefersDark) {
      document.documentElement.setAttribute('data-bs-theme', 'dark')
      document.body.classList.add('dark')
    }
  }
  // Light mode is default, no additional classes needed
}

// Load saved theme on mount
onMounted(() => {
  const savedTheme = localStorage.getItem('theme') as 'auto' | 'dark' | 'light' | null
  if (savedTheme) {
    setTheme(savedTheme)
  }

  // Listen for system theme changes when in auto mode
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (currentTheme.value === 'auto') {
      if (e.matches) {
        document.documentElement.setAttribute('data-bs-theme', 'dark')
        document.body.classList.add('dark')
      } else {
        document.documentElement.removeAttribute('data-bs-theme')
        document.body.classList.remove('dark')
      }
    }
  })
})

const menuItems = computed(() => {
  if (props.role === 'admin') {
    return [
      { label: 'Dashboard', path: '/admin', icon: 'fa-solid fa-gauge', isStatic: true },
      { label: 'Dashboard', path: '/admin', icon: 'fa-solid fa-gauge' },
      { label: 'Resepsionis', path: '/admin/receptionists', icon: 'fa-solid fa-user-tie' },
      { label: 'Daftar Feedback', path: '/admin/feedback', icon: 'fa-solid fa-comments' },
      { label: 'Daftar Tamu', path: '/admin/logs', icon: 'fa-solid fa-users' },
      { label: 'Keluar', path: '/logout', icon: 'fa-solid fa-right-from-bracket' }
    ]
  } else {
    return [
      { label: 'Dashboard', path: '/receptionist', icon: 'fa-solid fa-gauge', isStatic: true },
      { label: 'Dashboard', path: '/receptionist', icon: 'fa-solid fa-gauge' },
      { label: 'Daftar Tamu', path: '/receptionist/guests', icon: 'fa-solid fa-users' }
    ]
  }
})

const openSubmenu = ref<string | null>(null)

const toggleSubmenu = (label: string, event: Event) => {
  event.preventDefault()
  openSubmenu.value = openSubmenu.value === label ? null : label
}

const isActive = (path: string) => {
  return route.path === path
}

const isSubmenuActive = (item: any) => {
  if (!item.submenu) return false
  return item.submenu.some((sub: any) => route.path === sub.path)
}

const isSidebarOpen = ref(false)

const toggleSidebar = (event?: Event) => {
  if (event) event.preventDefault()
  
  // Check if we're on mobile or desktop (992px is Bootstrap's lg breakpoint)
  if (window.innerWidth < 992) {
    isSidebarOpen.value = !isSidebarOpen.value
    if (isSidebarOpen.value) {
      document.body.classList.add('sidebar-main')
    } else {
      document.body.classList.remove('sidebar-main')
    }
  } else {
    // Desktop: Toggle sidebar-mini
    document.body.classList.toggle('sidebar-mini')
  }
}

const isProfileOpen = ref(false)
const toggleProfile = (event?: Event) => {
  // Use Bootstrap's data-bs-toggle logic usually, but keep state for mobile
  isProfileOpen.value = !isProfileOpen.value
}

// Close dropdowns when clicking outside
onMounted(() => {
  document.addEventListener('click', (event) => {
    const target = event.target as HTMLElement
    // Close profile dropdown if clicked outside
    if (isProfileOpen.value && !target.closest('.profile-dropdown-container')) {
      isProfileOpen.value = false
    }
  })
})

// Watch for route changes to close sidebar on mobile
import { watch } from 'vue'
watch(route, () => {
  if (window.innerWidth < 992 && isSidebarOpen.value) {
    toggleSidebar()
  }
})

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <!-- Sidebar Start -->
  <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
      <router-link to="/" class="navbar-brand">
        <!--Logo start-->
        <img src="/assets/images/logo-new2.png" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded object-fit-cover">
        <!--logo End-->
        <h4 class="logo-title font-bold">BuTaKa</h4>
      </router-link>
      <div class="sidebar-toggle" data-toggle="sidebar" data-active="true" @click.stop="toggleSidebar">
        <i class="icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </i>
      </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
      <div class="sidebar-list">
        <!-- Sidebar Menu Start -->
        <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
          <template v-for="item in menuItems" :key="item.label">
            <li v-if="item.isStatic" class="nav-item static-item">
              <a class="nav-link static-item disabled" href="#" tabindex="-1">
                <span class="default-icon">{{ item.label }}</span>
                <span class="mini-icon"></span>
              </a>
            </li>
            <li v-else class="nav-item">
              <router-link class="nav-link" :class="{ active: isActive(item.path) }" :to="item.path">
                <i :class="item.icon"></i>
                <span class="item-name">{{ item.label }}</span>
              </router-link>
            </li>
          </template>
        </ul>
        <!-- Sidebar Menu End -->
      </div>
    </div>
    <div class="sidebar-footer"></div>
  </aside>
  <div class="sidebar-overlay" :class="{ 'show': isSidebarOpen }" @click="toggleSidebar"></div>
  <!-- Sidebar End -->

  <!-- Main Content Start -->
  <main class="main-content">
    <!-- Banner Start -->
    <div class="position-relative iq-banner">
      <!-- Nav Start -->
      <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
        <div class="container-fluid navbar-inner">
          <router-link to="/" class="navbar-brand">
            <h4 class="logo-title">BuTaKa</h4>
          </router-link>
          <div class="sidebar-toggle" data-toggle="sidebar" data-active="true" @click.stop="toggleSidebar">
            <i class="icon">
              <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
              </svg>
            </i>
          </div>

          <!-- Removed navbar-toggler for mobile -->

          <!-- Menu Start Header -->
          <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0 flex-row">
            <!-- Profile Dropdown -->
            <li class="nav-item dropdown profile-dropdown-container">
              <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" @click.stop="toggleProfile">
                <img src="/assets/images/logo-new2.png" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded object-fit-cover">
                <div class="caption ms-3 d-none d-md-block">
                  <h6 class="mb-0 caption-title">{{ role === 'admin' ? 'Admin' : 'Resepsionis' }}</h6>
                  <p class="mb-0 caption-sub-title">{{ role === 'admin' ? 'Administrator' : 'Receptionist' }}</p>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end bg-white" :class="{ 'show': isProfileOpen }" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#" @click.prevent="logout">Logout</a></li>
              </ul>
            </li>
            <!--- Profile Dropdown End -->
          </ul>
          <!-- Menu Start Header End-->
          <!-- Menu Start Header End-->
        </div>
      </nav>
      <!-- Nav End -->

      <!-- Nav Header Component Start -->
      <div class="iq-navbar-header" style="height: 215px">
        <div class="container-fluid iq-container">
          <div class="row">
            <div class="col-md-12">
              <div class="flex-wrap d-flex justify-content-between align-items-center">
                <slot name="header">
                  <div>
                    <h1>Dashboard For Admin BuTaKa</h1>
                    <p>Selamat datang di dashboard admin BuTaKa | Monitoring Buku Tamu Kantor.</p>
                  </div>
                </slot>
              </div>
            </div>
          </div>
        </div>
        <div class="iq-header-img">
          <img src="/assets/images/dashboard/top-header.png" alt="header"
            class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX" />
        </div>
      </div>
      <!-- Nav Header Component End -->
    </div>
    <!-- Banner End -->

    <!-- Content -->
    <div class="container-fluid content-inner mt-n5 py-0">
      <slot></slot>
    </div>
    <!-- Content End -->

    <!-- Footer Section Start -->
    <footer class="footer">
      <div class="footer-body">
        <ul class="left-panel list-inline mb-0 p-0">
          <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
          <li class="list-inline-item"><a href="#">Terms of Use</a></li>
        </ul>
        <div class="right-panel">
          © 2026 BuTaKa, Made with
          by <a href="https://iqonic.design/">IQONIC Design</a>.

        </div>
      </div>
    </footer>
    <!-- Footer Section End -->


    <!-- Setting Sidebar Start-->
    <a class="btn btn-fixed-end btn-warning btn-icon btn-setting" data-bs-toggle="offcanvas"
      data-bs-target="#offcanvasExample" role="button" aria-controls="offcanvasExample">
      <svg width="24" viewBox="0 0 24 24" class="animated-rotate icon-24" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd"
          d="M20.8064 7.62361L20.184 6.54352C19.6574 5.6296 18.4905 5.31432 17.5753 5.83872V5.83872C17.1397 6.09534 16.6198 6.16815 16.1305 6.04109C15.6411 5.91402 15.2224 5.59752 14.9666 5.16137C14.8021 4.88415 14.7137 4.56839 14.7103 4.24604V4.24604C14.7251 3.72922 14.5302 3.2284 14.1698 2.85767C13.8094 2.48694 13.3143 2.27786 12.7973 2.27808H11.5433C11.0367 2.27807 10.5511 2.47991 10.1938 2.83895C9.83644 3.19798 9.63693 3.68459 9.63937 4.19112V4.19112C9.62435 5.23693 8.77224 6.07681 7.72632 6.0767C7.40397 6.07336 7.08821 5.98494 6.81099 5.82041V5.82041C5.89582 5.29601 4.72887 5.61129 4.20229 6.52522L3.5341 7.62361C3.00817 8.53639 3.31916 9.70261 4.22975 10.2323V10.2323C4.82166 10.574 5.18629 11.2056 5.18629 11.8891C5.18629 12.5725 4.82166 13.2041 4.22975 13.5458V13.5458C3.32031 14.0719 3.00898 15.2353 3.5341 16.1454V16.1454L4.16568 17.2346C4.4124 17.6798 4.82636 18.0083 5.31595 18.1474C5.80554 18.2866 6.3304 18.2249 6.77438 17.976V17.976C7.21084 17.7213 7.73094 17.6516 8.2191 17.7822C8.70725 17.9128 9.12299 18.233 9.37392 18.6717C9.53845 18.9489 9.62686 19.2646 9.63021 19.587V19.587C9.63021 20.6435 10.4867 21.5 11.5433 21.5H12.7973C13.8502 21.5001 14.7053 20.6491 14.7103 19.5962V19.5962C14.7079 19.088 14.9086 18.6 15.2679 18.2407C15.6272 17.8814 16.1152 17.6807 16.6233 17.6831C16.9449 17.6917 17.2594 17.7798 17.5387 17.9394V17.9394C18.4515 18.4653 19.6177 18.1544 20.1474 17.2438V17.2438L20.8064 16.1454C21.0615 15.7075 21.1315 15.186 21.001 14.6964C20.8704 14.2067 20.55 13.7894 20.1108 13.5367V13.5367C19.6715 13.284 19.3511 12.8666 19.2206 12.3769C19.09 11.8873 19.16 11.3658 19.4151 10.928C19.581 10.6383 19.8211 10.3982 20.1108 10.2323V10.2323C21.0159 9.70289 21.3262 8.54349 20.8064 7.63277V7.63277V7.62361Z"
          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        <circle cx="12.1747" cy="11.8891" r="2.63616" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
          stroke-linejoin="round"></circle>
      </svg>
    </a>
    <!-- Setting Sidebar End -->

    <!-- offcanvas start -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" data-bs-scroll="true"
      data-bs-backdrop="true" aria-labelledby="offcanvasExampleLabel">
      <div class="offcanvas-header">
        <div class="d-flex align-items-center">
          <h3 class="offcanvas-title me-3" id="offcanvasExampleLabel">
            Settings
          </h3>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body data-scrollbar">
        <div class="row">
          <div class="col-lg-12">
            <h5 class="mb-3">Scheme</h5>
            <div class="d-grid gap-3 grid-cols-3 mb-4">
              <div class="btn btn-border" :class="{ active: currentTheme === 'auto' }" @click="setTheme('auto')">
                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill="currentColor" d="M7,2V13H10V22L17,10H13L17,2H7Z" />
                </svg>
                <span class="ms-2"> Auto </span>
              </div>

              <div class="btn btn-border" :class="{ active: currentTheme === 'dark' }" @click="setTheme('dark')">
                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill="currentColor"
                    d="M9,2C7.95,2 6.95,2.16 6,2.46C10.06,3.73 13,7.5 13,12C13,16.5 10.06,20.27 6,21.54C6.95,21.84 7.95,22 9,22A10,10 0 0,0 19,12A10,10 0 0,0 9,2Z" />
                </svg>
                <span class="ms-2"> Dark </span>
              </div>
              <div class="btn btn-border" :class="{ active: currentTheme === 'light' }" @click="setTheme('light')">
                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill="currentColor"
                    d="M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8M12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18M20,8.69V4H15.31L12,0.69L8.69,4H4V8.69L0.69,12L4,15.31V20H8.69L12,23.31L15.31,20H20V15.31L23.31,12L20,8.69Z" />
                </svg>
                <span class="ms-2"> Light</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


</main>
</template>

<style scoped>
/* Main Content Layout Fixes */
.main-content {
  transition: all 0.3s ease-in-out;
  position: relative;
  min-height: 100vh;
  overflow-x: hidden;
  max-width: 100vw;
}

/* Desktop: Push content when sidebar is present */
@media (min-width: 992px) {
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
  }

  .main-content {
    margin-left: 280px; /* Default sidebar width */
    max-width: calc(100vw - 280px);
  }

  /* When sidebar is minimized */
  body.sidebar-mini .main-content {
    margin-left: 80px;
    max-width: calc(100vw - 80px);
  }
}

/* Ensure navbar-collapse is always visible on desktop */
@media (min-width: 992px) {
  .navbar-collapse {
    display: flex !important;
    visibility: visible !important;
  }
}

/* Ensure dropdown menu displays properly */
.nav-item.dropdown .dropdown-menu {
  z-index: 1050;
  display: block;
  visibility: hidden;
  opacity: 0;
  transition: all 0.2s ease-in-out;
  transform: translateY(-10px);
}

.nav-item.dropdown .dropdown-menu.show {
  visibility: visible;
  opacity: 1;
  transform: translateY(0);
}

/* Mobile Sidebar Overlay */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  z-index: 900;
  display: none;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.sidebar-overlay.show {
  display: block;
  opacity: 1;
}

@media (max-width: 991.98px) {
  body.sidebar-main .sidebar {
    left: 0 !important;
    z-index: 1000;
    transform: translateX(0) !important;
    transition: all 0.3s ease-in-out;
  }
  
  .sidebar {
    left: -100% !important;
    transition: all 0.3s ease-in-out;
    position: fixed;
    top: 0;
    bottom: 0;
  }

  /* On mobile, main content should have zero margin and no overflow */
  .main-content {
    margin-left: 0 !important;
    max-width: 100vw !important;
    overflow-x: hidden !important;
  }

  /* Prevent horizontal scroll on navbar header */
  .iq-navbar-header,
  .iq-container,
  .container-fluid {
    max-width: 100vw;
    overflow-x: hidden;
  }

  /* Mobile Profile Dropdown Fixes */
  .profile-dropdown-container .dropdown-menu.show {
    position: absolute;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    transform: none !important;
    margin-top: 10px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0,0,0,0.05);
    min-width: 200px;
    z-index: 1050;
  }
}

.sidebar-toggle {
  cursor: pointer;
}
</style>
