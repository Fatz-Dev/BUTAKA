import { defineStore } from 'pinia'
import { ref } from 'vue'
import { feedbackApi } from '../services/api'

export interface Feedback {
    id: number
    name: string
    nama?: string
    institution: string
    instansi?: string
    rating: number
    comment: string
    pesan?: string
    created_at?: string
    updated_at?: string
}

export const useFeedbackStore = defineStore('feedback', () => {
    const feedbacks = ref<Feedback[]>([])
    const loading = ref(false)
    const error = ref<string | null>(null)

    // Fetch all feedback from API
    const fetchFeedback = async () => {
        loading.value = true
        error.value = null
        try {
            const response = await feedbackApi.getAll()
            feedbacks.value = response.data.data || response.data || []
            return feedbacks.value
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal memuat data feedback'
            console.error('Failed to fetch feedback:', err)
            return []
        } finally {
            loading.value = false
        }
    }

    // Add new feedback (public)
    const addFeedback = async (data: { name: string; institution: string; rating: number; comment: string }) => {
        loading.value = true
        error.value = null
        try {
            const response = await feedbackApi.create(data)
            const newFeedback = response.data.data || response.data
            feedbacks.value.unshift(newFeedback)
            return { success: true, data: newFeedback }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal mengirim feedback'
            console.error('Failed to add feedback:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    // Delete feedback (admin only)
    const deleteFeedback = async (id: number) => {
        loading.value = true
        error.value = null
        try {
            await feedbackApi.delete(id)
            feedbacks.value = feedbacks.value.filter(f => f.id !== id)
            return { success: true }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Gagal menghapus feedback'
            console.error('Failed to delete feedback:', err)
            return { success: false, message: error.value }
        } finally {
            loading.value = false
        }
    }

    return {
        feedbacks,
        loading,
        error,
        fetchFeedback,
        addFeedback,
        deleteFeedback
    }
})
