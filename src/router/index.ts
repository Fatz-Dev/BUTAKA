import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: () => import('../views/LandingPageView.vue')
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/LoginView.vue')
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
                    component: () => import('../views/receptionist/DashboardView.vue')
                },
                {
                    path: 'new-guest',
                    name: 'receptionist-new-guest',
                    component: () => import('../views/receptionist/GuestEntryView.vue')
                },
                {
                    path: 'guests',
                    name: 'receptionist-guests',
                    component: () => import('../views/receptionist/GuestListView.vue')
                }
            ],
            meta: { requiresAuth: true, role: 'receptionist' }
        }
    ]
})

export default router
