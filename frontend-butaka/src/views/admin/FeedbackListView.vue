<script setup lang="ts">
import { computed, onMounted } from 'vue'
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { useFeedbackStore } from '../../stores/feedback'
import Swal from 'sweetalert2'

const feedbackStore = useFeedbackStore()
const feedbacks = computed(() => feedbackStore.feedbacks)

// Confirm delete with SweetAlert
const confirmDelete = async (id: number, nama: string) => {
    const result = await Swal.fire({
        title: 'Hapus Feedback?',
        html: `<p>Anda yakin ingin menghapus feedback dari <b>${nama}</b>?</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fa fa-trash me-2"></i> Ya, Hapus',
        cancelButtonText: 'Batal'
    })

    if (result.isConfirmed) {
        const deleteResult = await feedbackStore.deleteFeedback(id)
        if (deleteResult.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Feedback telah dihapus.',
                icon: 'success',
                confirmButtonColor: '#3a57e8',
                timer: 2000,
                timerProgressBar: true
            })
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: deleteResult.message || 'Terjadi kesalahan saat menghapus.',
                icon: 'error',
                confirmButtonColor: '#3a57e8'
            })
        }
    }
}

// Fetch feedback on mount
onMounted(async () => {
    await feedbackStore.fetchFeedback()
    console.log('Feedbacks data:', feedbackStore.feedbacks)
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
                                <h6 class="mb-0 fw-bold">{{ fb.name || fb.nama }}</h6>
                                <small class="text-muted">{{ fb.instansi }}</small>
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
                            "{{ fb.comment || fb.pesan }}"
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fa-regular fa-clock me-1"></i>
                                {{ formatDate(fb.created_at) }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <button @click="confirmDelete(fb.id, fb.name)" class="btn btn-outline-danger border-0"
                                    title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
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
