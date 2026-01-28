import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: () => import('../views/LandingPageView.vue')
        },
        {
            path: '/logout',
            name: 'logout',
            component: () => import('../views/LogoutView.vue')
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/LoginView.vue'),
            meta: { guest: true }
        },
        {
            path: '/admin',
            component: () => import('../components/layout/EmptyRouterView.vue'),
            children: [
                {
                    path: '',
                    name: 'admin-dashboard',
                    component: () => import('../views/admin/DashboardView.vue')
                },
                {
                    path: 'receptionists',
                    name: 'admin-receptionists',
                    component: () => import('../views/admin/ManageReceptionistsView.vue')
                },
                {
                    path: 'receptionists/add',
                    name: 'admin-receptionists-add',
                    component: () => import('../views/admin/CreateReceptionistView.vue')
                },
                {
                    path: 'feedback',
                    name: 'admin-feedback',
                    component: () => import('../views/admin/FeedbackListView.vue')
                },
                {
                    path: 'logs',
                    name: 'admin-logs',
                    component: () => import('../views/admin/GuestLogsView.vue')
                }
            ],
            meta: { requiresAuth: true, role: 'admin' }
        },
        {
            path: '/receptionist',
            component: () => import('../components/layout/EmptyRouterView.vue'),
            children: [
                {
                    path: '',
                    name: 'receptionist-dashboard',
                    component: () => import('../views/receptionist/GuestListView.vue')
                }
            ],
            meta: { requiresAuth: true, role: 'resepsionis' }
        }
    ]
})

// Navigation guards
router.beforeEach((to, _from, next) => {
    const authStore = useAuthStore()

    // Initialize auth store from localStorage
    authStore.initialize()

    console.log('Router guard - isAuthenticated:', authStore.isAuthenticated)
    console.log('Router guard - user role:', authStore.user?.role)

    // Check if route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!authStore.isAuthenticated) {
            // Not logged in, redirect to login
            next({ name: 'login', query: { redirect: to.fullPath } })
            return
        }

        // Check role if specified
        const requiredRole = to.matched.find(record => record.meta.role)?.meta.role
        if (requiredRole) {
            const userRole = authStore.user?.role?.toLowerCase()

            if (requiredRole === 'admin' && userRole !== 'admin') {
                // User is not admin, redirect to receptionist
                next({ name: 'receptionist-dashboard' })
                return
            }
            if (requiredRole === 'resepsionis' && userRole !== 'resepsionis') {
                // User is not receptionist, redirect to admin
                next({ name: 'admin-dashboard' })
                return
            }
        }
    }

    // Redirect logged in users away from login page
    if (to.meta.guest && authStore.isAuthenticated) {
        const userRole = authStore.user?.role?.toLowerCase()
        if (userRole === 'admin') {
            next({ name: 'admin-dashboard' })
        } else {
            next({ name: 'receptionist-dashboard' })
        }
        return
    }

    next()
})

export default router
