import { defineStore } from 'pinia'
import { ref } from 'vue'
import { usersApi } from '../services/api'

export interface User {
    id: number
    name: string
    username: string
    email: string
    phone?: string
    role: 'admin' | 'resepsionis'
    is_active?: boolean | number
    created_at?: string
    updated_at?: string
}

export const useUsersStore = defineStore('users', () => {
    const users = ref<User[]>([])
    const loading = ref(false)
    const error = ref<string | null>(null)

    // Fetch all users from API
    const fetchUsers = async () => {
        loading.value = true
        error.value = null
        try {
            const response = await usersApi.getAll()
            users.value = response.data.data || response.data || []
            return users.value
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal memuat data pengguna'
            console.error('Failed to fetch users:', err)
            return []
        } finally {
            loading.value = false
        }
    }

    // Add new user
    const addUser = async (userData: { name: string; username: string; email: string; phone?: string; password: string; password_confirmation?: string; role: string }) => {
        loading.value = true
        error.value = null
        try {
            const response = await usersApi.create(userData)
            const newUser = response.data.data || response.data
            users.value.push(newUser)
            return { success: true, data: newUser }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal menambahkan pengguna'
            console.error('Failed to add user:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    // Update user
    const updateUser = async (id: number, userData: Partial<User>) => {
        loading.value = true
        error.value = null
        try {
            const response = await usersApi.update(id, userData)
            const updatedUser = response.data.data || response.data
            const index = users.value.findIndex(u => u.id === id)
            if (index !== -1) {
                users.value[index] = updatedUser
            }
            return { success: true, data: updatedUser }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal memperbarui pengguna'
            console.error('Failed to update user:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    // Delete user
    const deleteUser = async (id: number) => {
        loading.value = true
        error.value = null
        try {
            await usersApi.delete(id)
            users.value = users.value.filter(u => u.id !== id)
            return { success: true }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal menghapus pengguna'
            console.error('Failed to delete user:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    // Filter only receptionists
    const getReceptionists = () => {
        return users.value.filter(u => u.role?.toLowerCase() === 'resepsionis')
    }

    return {
        users,
        loading,
        error,
        fetchUsers,
        addUser,
        updateUser,
        deleteUser,
        getReceptionists
    }
})
