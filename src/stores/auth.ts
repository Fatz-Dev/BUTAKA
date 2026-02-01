import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authApi } from '../services/api'

export interface User {
    id: number
    name: string
    username: string
    email: string
    role: 'admin' | 'resepsionis'
    phone?: string
    avatar?: string
}

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null)
    const token = ref<string | null>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    // Computed
    const isAuthenticated = computed(() => !!token.value)
    const isAdmin = computed(() => user.value?.role?.toLowerCase() === 'admin')
    const isReceptionist = computed(() => user.value?.role?.toLowerCase() === 'resepsionis')

    // Initialize from localStorage (only if not already in memory)
    const initialize = () => {
        // Skip if already authenticated in memory
        if (token.value && user.value) {
            console.log('Auth already initialized in memory')
            return
        }

        const savedToken = localStorage.getItem('auth_token')
        const savedUser = localStorage.getItem('user_data')

        console.log('Initializing auth from localStorage:', { hasToken: !!savedToken, hasUser: !!savedUser })

        if (savedToken && !token.value) {
            token.value = savedToken
        }
        if (savedUser && !user.value) {
            try {
                user.value = JSON.parse(savedUser)
                console.log('Loaded user from localStorage:', user.value)
            } catch (e) {
                console.error('Failed to parse user data:', e)
            }
        }
    }

    // Login
    const login = async (email: string, password: string) => {
        loading.value = true
        error.value = null
        try {
            const response = await authApi.login(email, password)
            const data = response.data

            console.log('Login response:', data) // Debug log

            // Save token (handle different response formats)
            // API returns: { success: true, data: { access_token: '...', user: {...} } }
            const extractedToken = data.token || data.access_token || data.data?.access_token || data.data?.token
            console.log('Extracted token:', extractedToken ? 'yes' : 'no')

            if (extractedToken) {
                token.value = extractedToken
                localStorage.setItem('auth_token', extractedToken)
                console.log('Token saved to localStorage')
            } else {
                console.error('No token found in response!')
            }

            // Save user data (handle different response formats)
            const userData = data.user || data.data?.user
            if (userData) {
                user.value = userData
                localStorage.setItem('user_data', JSON.stringify(user.value))
                console.log('User data saved:', user.value) // Debug log
                console.log('User role:', user.value?.role) // Debug log
            }

            return { success: true, user: user.value }
        } catch (err: any) {
            const message = err.response?.data?.message || 'Login gagal. Periksa email dan password Anda.'
            error.value = message
            return { success: false, message }
        } finally {
            loading.value = false
        }
    }

    // Logout
    const logout = async () => {
        loading.value = true
        try {
            await authApi.logout()
        } catch (err) {
            console.error('Logout error:', err)
        } finally {
            // Clear local state regardless of API result
            token.value = null
            user.value = null
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user_data')
            loading.value = false
        }
    }

    // Fetch current user profile
    const fetchProfile = async () => {
        if (!token.value) return null
        loading.value = true
        try {
            const response = await authApi.getProfile()
            user.value = response.data.user || response.data
            localStorage.setItem('user_data', JSON.stringify(user.value))
            return user.value
        } catch (err) {
            console.error('Failed to fetch profile:', err)
            return null
        } finally {
            loading.value = false
        }
    }

    // Initialize on store creation
    initialize()

    return {
        user,
        token,
        loading,
        error,
        isAuthenticated,
        isAdmin,
        isReceptionist,
        login,
        logout,
        fetchProfile,
        initialize
    }
})
