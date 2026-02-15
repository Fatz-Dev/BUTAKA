<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { useFeedbackStore } from '../../stores/feedback'

const feedbackStore = useFeedbackStore()
const selectedRating = ref<number>(0) // 0 means all ratings

const feedbacks = computed(() => {
    if (selectedRating.value === 0) {
        return feedbackStore.feedbacks
    }
    return feedbackStore.feedbacks.filter(fb => fb.rating === selectedRating.value)
})

// Fetch feedback on mount
onMounted(async () => {
    await feedbackStore.fetchFeedback()
})

const formatDate = (dateString: string | null | undefined) => {
    if (!dateString) return '-'
    try {
        const date = new Date(dateString)
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }).format(date)
    } catch (e) {
        return dateString
    }
}

</script>

<template>
    <DashboardLayout role="admin">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-comments me-2"></i>Daftar Feedback Tamu
                        </h5>
                        <div class="d-flex align-items-center">
                            <label class="me-2 text-muted small fw-bold">Filter Rating:</label>
                            <select v-model="selectedRating" class="form-select form-select-sm" style="width: auto;">
                                <option :value="0">Semua Bintang</option>
                                <option v-for="i in 5" :key="i" :value="6 - i">
                                    {{ 6 - i }} Bintang
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="feedback-container">
            <div v-for="fb in feedbacks" :key="fb.id" class="col-xl-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 feedback-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ fb.visitor?.name }}</h6>
                                <small class="text-muted">{{ fb.visitor?.institution }}</small>
                            </div>
                            <div class="ms-auto">
                                <div class="text-warning">
                                    <i v-for="i in 5" :key="i"
                                        :class="[i <= fb.rating ? 'fa-solid fa-star' : 'fa-regular fa-star']"
                                        class="small me-1">
                                    </i>
                                </div>
                            </div>
                        </div>
                        <p class="card-text text-secondary mb-4 italic" style="font-style: italic">
                            "{{ fb.comment }}"
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fa-regular fa-clock me-1"></i>
                                {{ formatDate(fb.created_at) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="feedbackStore.loading" class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Memuat data...</p>
            </div>

            <div v-else-if="feedbacks.length === 0" class="col-12 text-center py-5">
                <div class="card py-5 shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa-solid fa-comment-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada feedback dari tamu</h5>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.feedback-card {
    transition: transform 0.2s;
}

.feedback-card:hover {
    transform: translateY(-5px);
}

.italic {
    font-style: italic;
}
</style>
