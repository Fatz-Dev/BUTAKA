<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

const goHome = () => router.push('/')
const goBack = () => router.back()
</script>

<template>
    <div class="not-found-page">
        <div class="not-found-container">
            <!-- Animated 404 -->
            <div class="error-code">
                <span class="digit">4</span>
                <span class="digit zero">
                    <i class="fa fa-compass"></i>
                </span>
                <span class="digit">4</span>
            </div>

            <h1 class="title">Halaman Tidak Ditemukan</h1>
            <p class="description">
                Maaf, halaman yang Anda cari tidak ada
            </p>

            <div class="actions">
                <button @click="goHome" class="btn-primary">
                    <i class="fa fa-home me-2"></i>Kembali ke Beranda
                </button>
                <button @click="goBack" class="btn-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Halaman Sebelumnya
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.not-found-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    font-family: 'Inter', sans-serif;
    padding: 2rem;
}

.not-found-container {
    text-align: center;
    max-width: 500px;
}

.error-code {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
}

.digit {
    font-size: 7rem;
    font-weight: 900;
    color: #3a57e8;
    line-height: 1;
    text-shadow: 0 0 40px rgba(58, 87, 232, 0.3);
}

.digit.zero {
    display: flex;
    align-items: center;
    justify-content: center;
    animation: spin 4s linear infinite;
}

.digit.zero i {
    font-size: 5.5rem;
    color: #f59e0b;
    filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.4));
}


.title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #f1f5f9;
    margin-bottom: 0.75rem;
}

.description {
    font-size: 1rem;
    color: #94a3b8;
    margin-bottom: 2.5rem;
    line-height: 1.6;
}

.actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    align-items: center;
}

.btn-primary {
    padding: 0.85rem 2rem;
    background: linear-gradient(135deg, #3a57e8, #6366f1);
    color: white;
    border: none;
    border-radius: 0.75rem;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(58, 87, 232, 0.4);
    width: 100%;
    max-width: 280px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(58, 87, 232, 0.5);
}

.btn-secondary {
    padding: 0.85rem 2rem;
    background: transparent;
    color: #94a3b8;
    border: 1px solid #334155;
    border-radius: 0.75rem;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    width: 100%;
    max-width: 280px;
}

.btn-secondary:hover {
    background: #1e293b;
    color: #e2e8f0;
    border-color: #475569;
}

@media (min-width: 640px) {
    .actions {
        flex-direction: row;
        justify-content: center;
    }
}
</style>
