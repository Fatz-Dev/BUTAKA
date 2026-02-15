# BuTaKa - Buku Tamu Kantor (Frontend)

BuTaKa (Buku Tamu Kantor) adalah aplikasi frontend berbasis web untuk sistem manajemen buku tamu digital. Aplikasi ini dibangun menggunakan Vue 3 dengan Composition API dan TypeScript untuk memastikan performa tinggi dan keamanan tipe data.

---

## Fitur Utama

### 1. Panel Publik (Tamu)
- Self Check-in: Form pendaftaran kunjungan mandiri yang responsif.
- Feedback: Pengiriman rating dan komentar setelah kunjungan selesai.
- Daftar Kunjungan Terkini: Menampilkan 5 tamu terakhir yang berkunjung.

### 2. Panel Resepsionis
- Monitoring Real-time: Daftar tamu yang sedang berkunjung khusus untuk hari ini.
- Check-out Sistem: Fitur untuk menandai tamu yang telah selesai urusannya.
- Dark Mode: Dukungan tema gelap untuk kenyamanan penggunaan durasi lama.

### 3. Panel Admin
- Dashboard Statistik: Visualisasi data kunjungan menggunakan ApexCharts (Area Sparkline, Radial Bar, dan Bar Chart).
- Manajemen Resepsionis: CRUD data akun resepsionis.
- Laporan Kunjungan: Riwayat lengkap semua kunjungan tamu.
- Manajemen Feedback: Memantau dan mengelola masukan dari pengunjung.

---

## Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| Framework | Vue 3 (Composition API) |
| Build Tool | Vite 7 |
| Language | TypeScript 5.9 |
| State Management | Pinia 3 |
| Routing | Vue Router 4 |
| Charting | VueApexCharts / ApexCharts |
| Styling | Tailwind CSS + Hope UI Assets |
| UI Feedback | SweetAlert2 |

---

## Prasyarat

Sebelum memulai, pastikan perangkat Anda sudah terpasang:
- Node.js (versi 18 ke atas)
- npm atau yarn
- Backend API (Laravel) sudah berjalan di http://localhost:8000

---

## Panduan Setup dan Instalasi

### 1. Clone Repositori
Silakan buka terminal dan jalankan perintah berikut:
```bash
git clone <repository-url>
cd frontend-butaka
```

### 2. Instalasi Dependency
Unduh semua library yang dibutuhkan dengan menjalankan:
```bash
npm install
```

### 3. Konfigurasi Environment
Buat file .env di root folder (opsional, default menggunakan localhost:8000):
```env
VITE_API_BASE_URL=http://localhost:8000/api
```

### 4. Jalankan Development Server
Mulai aplikasi dalam mode pengembangan:
```bash
npm run dev
```
Aplikasi akan tersedia di alamat: http://localhost:5173

---

## Struktur Proyek

```
frontend-butaka/
├── public/                 # Aset statis (gambar, favicon)
├── src/
│   ├── components/         # Komponen reusable
│   │   ├── layout/         # DashboardLayout, Sidebar, Navbar
│   │   └── error/          # NotFoundView.vue (Halaman 404)
│   ├── router/             # Konfigurasi Vue Router
│   ├── services/           # Konfigurasi Axios dan API layer
│   ├── stores/             # Global State (Pinia)
│   │   ├── auth.ts         # Autentikasi & Role
│   │   ├── guestLogs.ts    # Data Tamu
│   │   └── feedback.ts     # Data Rating
│   └── views/              # Halaman Utama (Views)
│       ├── admin/          # Panel Khusus Admin
│       ├── receptionist/   # Panel Khusus Resepsionis
│       └── LandingPage.vue # Halaman Publik
├── package.json            # Daftar dependency dan scripts
└── vite.config.ts          # Konfigurasi Vite
```

---

## Halaman dan Hak Akses

| Route | Akses | Fungsi |
|-------|-------|--------|
| / | Publik | Check-in mandiri dan feedback |
| /login | Publik | Masuk ke sistem staff |
| /admin | Admin | Dashboard dan statistik utama |
| /receptionist | Staff | Monitoring tamu hari ini |
| /admin/logs | Admin | Riwayat semua kunjungan |

Aplikasi juga dilengkapi dengan halaman Error 404 otomatis jika pengguna mengakses URL yang tidak terdaftar.

---

## Build untuk Produksi

Jika ingin melakukan kompilasi file untuk deployment:

1. Jalankan perintah build:
```bash
npm run build
```

2. Hasil build akan berada di dalam folder `dist/`.

3. Uji hasil build secara lokal:
```bash
npm run preview
```

---

## Lisensi dan Hak Cipta

Copyright 2025 BuTaKa - Proyek Magang.
Seluruh hak cipta dilindungi.
