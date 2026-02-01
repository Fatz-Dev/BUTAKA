<script setup lang="ts">
import { Moon, BookOpen } from 'lucide-vue-next'
</script>

<template>
  <div class="public-layout">
    <header class="navbar">
      <div class="container navbar-content">
        <div class="brand">
          <div class="logo-circle">
            <BookOpen class="logo-icon" :size="24" />
          </div>
          <div class="brand-text">
            <h1>BuTaKa</h1>
            <p>Buku Tamu Digital</p>
          </div>
        </div>

        <button class="icon-btn-dark">
          <Moon :size="20" />
        </button>
      </div>
    </header>

    <main class="main-content">
      <div class="container">
        <slot></slot>
      </div>
    </main>

    <footer class="footer">
      <div class="container center-text">
        <p>Project Magang</p>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.public-layout {
  min-height: 100vh;
  background-color: #f1f5f9;
  font-family: 'Inter', sans-serif;
  display: flex;
  flex-direction: column;
}

/* Header - Keeping the Diskominfo Blue Theme */
.navbar {
  background-color: var(--color-primary);
  /* Royal Blue */
  color: white;
  padding: 1rem 0;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.navbar-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo-circle {
  background-color: white;
  color: var(--color-primary);
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand-text h1 {
  font-size: 1.5rem;
  font-weight: 800;
  line-height: 1;
  letter-spacing: -0.5px;
}

.brand-text p {
  font-size: 0.8rem;
  opacity: 0.9;
  font-weight: 400;
}

.icon-btn-dark {
  background: rgba(255, 255, 255, 0.1);
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: white;
  transition: background 0.2s;
}

.icon-btn-dark:hover {
  background: rgba(255, 255, 255, 0.2);
}

.main-content {
  flex: 1;
  padding-top: 2rem;
  padding-bottom: 4rem;
}

.footer {
  background-color: var(--color-primary-dark);
  color: white;
  padding: 1.5rem 0;
  margin-top: auto;
  font-size: 0.875rem;
  opacity: 0.9;
}

.center-text {
  text-align: center;
}
</style>
