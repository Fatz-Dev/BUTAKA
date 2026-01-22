import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface GuestRecord {
    id: string
    nama: string
    instansi: string
    telepon: string
    email: string
    tujuan_staf: string
    keperluan: string
    waktu: string
    status: 'aktif' | 'selesai'
}

export const useGuestLogsStore = defineStore('guestLogs', () => {
    const logs = ref<GuestRecord[]>([])

    // Load logs from localStorage
    const loadLogs = () => {
        const savedLogs = localStorage.getItem('guest_logs')
        if (savedLogs) {
            logs.value = JSON.parse(savedLogs)
        }
    }

    // Save logs to localStorage
    const saveLogs = () => {
        localStorage.setItem('guest_logs', JSON.stringify(logs.value))
    }

    // Add new guest
    const addGuest = (guest: Omit<GuestRecord, 'id' | 'waktu' | 'status'>) => {
        const newGuest: GuestRecord = {
            ...guest,
            id: Date.now().toString(),
            waktu: new Date().toLocaleString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }),
            status: 'aktif'
        }
        logs.value.unshift(newGuest)
        saveLogs()
    }

    // Update status to selesai (checkout)
    const checkoutGuest = (id: string) => {
        const index = logs.value.findIndex(l => l.id === id)
        if (index !== -1) {
            logs.value[index].status = 'selesai'
            saveLogs()
        }
    }

    // Delete guest log
    const deleteGuest = (id: string) => {
        logs.value = logs.value.filter(l => l.id !== id)
        saveLogs()
    }

    // Real-time listener for cross-tab sync
    const handleStorageChange = (event: StorageEvent) => {
        if (event.key === 'guest_logs') {
            loadLogs()
        }
    }

    // Initialize
    loadLogs()
    window.addEventListener('storage', handleStorageChange)

    return { logs, addGuest, checkoutGuest, deleteGuest }
})
