import { defineStore } from 'pinia'
import { ref } from 'vue'
import { visitorsApi } from '../services/api'

export interface GuestRecord {
    id: number
    name: string
    institution: string
    instansi?: string
    phone: string
    email: string
    host_name: string
    purpose: string
    check_in_time: string
    check_out_time?: string
    status: 'aktif' | 'selesai'
    created_at?: string
    updated_at?: string
}

export const useGuestLogsStore = defineStore('guestLogs', () => {
    const logs = ref<GuestRecord[]>([])
    const loading = ref(false)
    const error = ref<string | null>(null)

    // Fetch all visitors from API
    const fetchVisitors = async () => {
        loading.value = true
        error.value = null
        try {
            const response = await visitorsApi.getAll()
            logs.value = response.data.data || response.data || []
            return logs.value
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal memuat data pengunjung'
            console.error('Failed to fetch visitors:', err)
            return []
        } finally {
            loading.value = false
        }
    }

    // Add new guest (public self check-in)
    const addGuest = async (guest: Omit<GuestRecord, 'id' | 'check_in_time' | 'check_out_time' | 'status' | 'created_at' | 'updated_at'>) => {
        loading.value = true
        error.value = null
        try {
            const response = await visitorsApi.create(guest)
            const newGuest = response.data.data || response.data
            logs.value.unshift(newGuest)
            return { success: true, data: newGuest }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal mendaftarkan kunjungan'
            console.error('Failed to add guest:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    // Checkout guest
    const checkoutGuest = async (id: number) => {
        loading.value = true
        error.value = null
        try {
            await visitorsApi.checkout(id)
            // Update local state
            const index = logs.value.findIndex(l => l.id === id)
            if (index !== -1) {
                logs.value[index].status = 'selesai'
            }
            return { success: true }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal melakukan checkout'
            console.error('Failed to checkout guest:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    // Delete guest (admin only)
    const deleteGuest = async (id: number) => {
        loading.value = true
        error.value = null
        try {
            await visitorsApi.delete(id)
            logs.value = logs.value.filter(l => l.id !== id)
            return { success: true }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal menghapus data pengunjung'
            console.error('Failed to delete guest:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    return {
        logs,
        loading,
        error,
        fetchVisitors,
        addGuest,
        checkoutGuest,
        deleteGuest
    }
})
