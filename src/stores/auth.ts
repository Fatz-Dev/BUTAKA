import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
    const user = ref<{ role: string } | null>(null)

    const login = (role: string) => {
        user.value = { role }
    }

    return { user, login }
})
