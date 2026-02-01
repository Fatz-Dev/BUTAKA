<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

onMounted(async () => {
    await authStore.logout()
    router.push('/login')
})
</script>

<template>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Logging out...</span>
        </div>
    </div>
</template>
