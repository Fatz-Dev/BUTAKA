<script setup lang="ts">
import { CheckCircle, XCircle, Info } from 'lucide-vue-next'
import { onMounted, onUnmounted } from 'vue'

const props = defineProps<{
  show: boolean
  message: string
  type?: 'success' | 'error' | 'info'
}>()

const emit = defineEmits(['close'])

// Auto close after 3 seconds
let timer: ReturnType<typeof setTimeout>

onMounted(() => {
  if (props.show) {
    startTimer()
  }
})

onUnmounted(() => {
  clearTimeout(timer)
})

const startTimer = () => {
  clearTimeout(timer)
  timer = setTimeout(() => {
    emit('close')
  }, 3000)
}
</script>

<template>
  <Transition name="toast">
    <div v-if="show" class="toast-container" :class="type || 'success'">
      <div class="toast-icon">
        <CheckCircle v-if="type === 'success' || !type" :size="20" />
        <XCircle v-else-if="type === 'error'" :size="20" />
        <Info v-else :size="20" />
      </div>
      <div class="toast-message">{{ message }}</div>
    </div>
  </Transition>
</template>

<style scoped>
.toast-container {
  position: fixed;
  top: 2rem;
  right: 2rem;
  padding: 1rem 1.5rem;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  box-shadow: var(--shadow-lg);
  z-index: 1000;
  min-width: 300px;
  backdrop-filter: blur(8px);
  color: white;
  font-weight: 500;
}

.toast-container.success {
  background-color: rgba(16, 185, 129, 0.9); /* Emerald 500 */
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.toast-container.error {
  background-color: rgba(239, 68, 68, 0.9); /* Red 500 */
  border: 1px solid rgba(239, 68, 68, 0.2);
}

.toast-container.info {
  background-color: rgba(59, 130, 246, 0.9); /* Blue 500 */
  border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Animations */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(0.9);
}
</style>
