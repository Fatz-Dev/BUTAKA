import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api'

// Create axios instance
const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

// Request interceptor - add auth token to every request
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Response interceptor - handle 401 unauthorized
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Clear token and redirect to login
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user_data')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

// Auth API
export const authApi = {
    login: (email: string, password: string) =>
        api.post('/auth/login', { email, password }),
    logout: () =>
        api.post('/auth/logout'),
    getProfile: () =>
        api.get('/auth/me'),
    updateProfile: (data: object) =>
        api.put('/auth/profile', data),
    changePassword: (data: { current_password: string; new_password: string }) =>
        api.post('/auth/change-password', data),
    register: (data: object) =>
        api.post('/auth/register', data)
}

// Users API (Admin only)
export const usersApi = {
    getAll: () =>
        api.get('/users'),
    getById: (id: number) =>
        api.get(`/users/${id}`),
    create: (data: object) =>
        api.post('/users', data),
    update: (id: number, data: object) =>
        api.put(`/users/${id}`, data),
    delete: (id: number) =>
        api.delete(`/users/${id}`)
}

// Visitors API
export const visitorsApi = {
    getAll: () =>
        api.get('/visitors'),
    getById: (id: number) =>
        api.get(`/visitors/${id}`),
    create: (data: object) =>
        api.post('/visitors', data),
    update: (id: number, data: object) =>
        api.put(`/visitors/${id}`, data),
    updateStatus: (id: number, status: string) =>
        api.put(`/visitors/${id}/status`, { status }),
    checkout: (id: number) =>
        api.post(`/visitors/${id}/checkout`),
    delete: (id: number) =>
        api.delete(`/visitors/${id}`)
}

// Feedback API
export const feedbackApi = {
    getAll: () =>
        api.get('/feedback'),
    getById: (id: number) =>
        api.get(`/feedback/${id}`),
    create: (data: object) =>
        api.post('/feedback', data),
    delete: (id: number) =>
        api.delete(`/feedback/${id}`)
}

// Dashboard API (Admin only)
export const dashboardApi = {
    getStats: () =>
        api.get('/dashboard/stats'),
    getRecentVisitors: () =>
        api.get('/dashboard/recent-visitors'),
    getRatingBreakdown: () =>
        api.get('/dashboard/rating-breakdown'),
    getVisitorTrends: () =>
        api.get('/dashboard/visitor-trends')
}

export default api
