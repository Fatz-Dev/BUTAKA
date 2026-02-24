# BuTaKa - Buku Tamu Kantor

Sistem Manajemen Buku Tamu Digital terintegrasi untuk Guest Management pada lingkungan perkantoran. Proyek ini merupakan monorepo yang terdiri dari backend berbasis Laravel 12 dan frontend berbasis Vue.js 3 dengan TypeScript.

---

## Deskripsi Proyek

BuTaKa (Buku Tamu Kantor) dirancang untuk menggantikan buku tamu fisik dengan sistem digital yang lebih efisien, akurat, dan memiliki fitur pelaporan yang lengkap.

Fitur utama sistem ini meliputi:
- Registrasi tamu mandiri (Self Check-in) di panel publik.
- Dashboard statistik real-time dengan visualisasi data interaktif (ApexCharts).
- Manajemen data tamu harian khusus untuk resepsionis.
- Sistem umpan balik (feedback) pengunjung terintegrasi.
- Manajemen akun staff (Admin dan Resepsionis).

Fitur-fitur terbaru yang telah ditambahkan:
- **Single URL Deployment (SPA Merge)**: Vue SPA kini dapat di-build langsung ke folder `public/` Laravel, sehingga frontend dan backend berjalan dalam 1 URL/domain.
- **Pembaruan Branding**: Logo sistem telah diperbarui menggunakan Logo Kota Banda Aceh untuk identitas yang lebih resmi.
- **Filter Lanjutan Admin**: Panel admin kini dilengkapi fitur filter pada daftar feedback dan log kunjungan tamu untuk mempermudah pencarian data.
- **Dropdown Pengunjung Dinamis**: Form feedback kini menggunakan dropdown pencarian yang hanya menampilkan daftar tamu yang memang benar-benar telah berkunjung.
- **Filter Harian Resepsionis**: Halaman daftar tamu pada role resepsionis otomatis difilter untuk hanya menampilkan kunjungan pada hari ini saja (Today Only).
- **Halaman Error 404**: Penanganan rute yang tidak valid melalui halaman "Not Found" yang informatif dan user-friendly.
- **Optimasi UI/UX**: Penyeragaman ukuran kartu statistik (stat-cards) dan perbaikan tata letak agar lebih presisi di berbagai ukuran layar.

---

## Struktur Repositori

```
BUTAKA/
├── backend-butaka/      # REST API (Laravel 12)
├── frontend-butaka/     # Single Page Application (Vue.js 3 + Vite)
└── README.md            # Dokumentasi Utama (File ini)
```

---

## Panduan Instalasi Cepat

### Prasyarat Sistem

Pastikan perangkat Anda memiliki:
- PHP 8.2 ke atas
- Node.js 18 ke atas
- Composer 2.x
- MariaDB atau MySQL (disarankan via XAMPP)
- Git

### Langkah 1: Clone Repositori

```bash
git clone <repository-url>
cd BUTAKA
```

### Langkah 2: Setup Backend (Laravel)

```bash
cd backend-butaka
composer install
cp .env.example .env
php artisan key:generate
# Sesuaikan konfigurasi database di file .env
php artisan migrate --seed
php artisan serve
```

### Langkah 3: Setup Frontend (Vue.js)

```bash
cd ../frontend-butaka
npm install
npm run dev
```

**Mode Development (2 server terpisah):**
- Frontend: `http://localhost:5173`
- Backend API: `http://localhost:8000`

### Langkah 4: Build Produksi (1 URL)

Untuk menggabungkan frontend dan backend menjadi **1 URL**, cukup build Vue:

```bash
cd frontend-butaka
npm run build
```

Hasil build otomatis masuk ke `backend-butaka/public/`. Lalu jalankan Laravel saja:

```bash
cd ../backend-butaka
php artisan serve
```

Akses `http://localhost:8000` — Vue SPA + API dalam 1 URL.

---

## Tech Stack Utama

### Backend
- Framework: Laravel 12
- Autentikasi: Laravel Sanctum
- Database: MySQL / MariaDB
- Containerization: Docker (siap deployment)

### Frontend
- Library Utama: Vue.js 3 (Composition API)
- Bahasa: TypeScript
- State Management: Pinia
- Charts: ApexCharts
- CSS Framework: Tailwind CSS + Bootstrap 5 (Hope UI)

---

## User Roles dan Kredensial Default

Setelah menjalankan `php artisan db:seed`, Anda dapat masuk dengan akun berikut:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@butaka.com | password |
| Resepsionis | resepsionis1@butaka.com | password |

---

## Dokumentasi Teknis Lanjutan

Untuk informasi lebih detail mengenai konfigurasi teknis, silakan merujuk pada dokumentasi spesifik di masing-masing direktori:

1. [Dokumentasi Backend (Laravel)](./backend-butaka/README.md)
   - Berisi detail API Endpoints, struktur database, dan konfigurasi Docker.
2. [Dokumentasi Frontend (Vue.js)](./frontend-butaka/README.md)
   - Berisi detail komponen, state management (Pinia), dan panduan build produksi.

---

## Lisensi dan Hak Cipta

Copyright 2025 BuTaKa - Proyek Magang.
Seluruh hak cipta dilindungi. Dibuat untuk tujuan efisiensi administrasi manajemen tamu kantor.