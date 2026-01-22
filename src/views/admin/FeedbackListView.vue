<script setup lang="ts">
import DashboardLayout from '../../components/layout/DashboardLayout.vue'
import { ref } from 'vue'

interface Feedback {
    id: number
    guestName: string
    company: string
    rating: number
    comment: string
    date: string
}

const feedbacks = ref<Feedback[]>([
    {
        id: 1,
        guestName: 'Ahmad Dhani',
        company: 'PT. Maju Mundur',
        rating: 5,
        comment: 'Pelayanan sangat ramah dan proses cepat. Terima kasih!',
        date: '14 Jan 2026, 09:30'
    },
    {
        id: 2,
        guestName: 'Sarah Tumiwa',
        company: 'SMK N 1 Jakarta',
        rating: 4,
        comment: 'Fasilitas ruang tunggu sangat nyaman dan bersih.',
        date: '13 Jan 2026, 14:15'
    },
    {
        id: 3,
        guestName: 'Budi Hartono',
        company: 'Bank Central Asia',
        rating: 5,
        comment: 'Sangat puas dengan sistem digitalnya, tidak perlu antre lama.',
        date: '12 Jan 2026, 11:00'
    },
    {
        id: 4,
        guestName: 'Dewi Lestari',
        company: 'Telkom Indonesia',
        rating: 3,
        comment: 'Aplikasi bagus, tapi mungkin bisa ditambahkan fitur scan QR.',
        date: '11 Jan 2026, 15:45'
    }
])

const isDeleteModalOpen = ref(false)
const selectedFeedback = ref<Feedback | null>(null)

const confirmDelete = (id: number) => {
    const fb = feedbacks.value.find(f => f.id === id)
    if (fb) {
        selectedFeedback.value = fb
        isDeleteModalOpen.value = true
    }
}

const deleteFeedback = () => {
    if (selectedFeedback.value) {
        feedbacks.value = feedbacks.value.filter(f => f.id !== selectedFeedback.value?.id)
        isDeleteModalOpen.value = false
        selectedFeedback.value = null
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
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fa-solid fa-filter me-1"></i> Filter
                            </button>
                            <button class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-download me-1"></i> Export
                            </button>
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
                                <h6 class="mb-0 fw-bold">{{ fb.guestName }}</h6>
                                <small class="text-muted">{{ fb.company }}</small>
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
                                {{ fb.date }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <button @click="confirmDelete(fb.id)" class="btn btn-outline-danger border-0"
                                    title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="feedbacks.length === 0" class="col-12 text-center py-5">
                <div class="card py-5 shadow-sm border-0">
                    <div class="card-body">
                        <i class="fa-solid fa-comment-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada feedback dari tamu</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="isDeleteModalOpen" class="modal-backdrop fade show" @click="isDeleteModalOpen = false"></div>
        <div v-if="isDeleteModalOpen" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" @click="isDeleteModalOpen = false"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus feedback dari <strong>{{ selectedFeedback?.guestName
                                }}</strong>?</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" @click="isDeleteModalOpen = false">Batal</button>
                        <button type="button" class="btn btn-danger" @click="deleteFeedback">Hapus</button>
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

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040;
}

.modal {
    z-index: 1050;
}
</style>
