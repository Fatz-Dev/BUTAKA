# BuTaKa Backend - Sistem Manajemen Buku Tamu Kantor

API Backend untuk sistem manajemen tamu "BuTaKa" (Buku Tamu Kantor), dibangun menggunakan framework Laravel 12.

---

## Fitur Utama

- Autentikasi: Sistem login aman menggunakan Laravel Sanctum (Token-based).
- Manajemen User: CRUD user dengan dua role (Admin dan Resepsionis), termasuk kontrol status aktif/non-aktif.
- Manajemen Pengunjung (Visitor): Pencatatan tamu masuk (self check-in), pemantauan status (menunggu, berkunjung, selesai), dan check-out oleh resepsionis.
- Sistem Feedback: Pengumpulan rating (1-5) dan komentar dari pengunjung, terhubung langsung ke data tamu melalui relasi `visitor_id`.
- Dashboard Ringkasan: Statistik real-time, tren pengunjung 7 hari terakhir, dan breakdown rating.
- Manajemen Profil: Ganti password, update info profil, dan upload foto (avatar).
- Single URL Deployment: Vue SPA dapat di-build ke `public/` Laravel sehingga frontend dan API berjalan dalam 1 domain tanpa konfigurasi CORS.

---

## Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL / MariaDB |
| Auth | Laravel Sanctum (Token-based) |
| Containerization | Docker (multi-stage build) |
| Tools | Composer, Artisan, XAMPP (lokal) |

---

## Prasyarat

- PHP >= 8.2
- Composer
- MySQL/MariaDB (disarankan menggunakan XAMPP)
- Git
- Docker (opsional, untuk deployment)

---

## Cara Setup dan Instalasi

### 1. Clone Repositori

```bash
git clone <repository-url>
cd backend-butaka
```

### 2. Instalasi Dependency

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_butaka
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Migrasi dan Seeder Database

Pastikan server database (MySQL) sudah berjalan, lalu jalankan:

```bash
php artisan migrate --seed
```

Perintah ini akan menjalankan tiga buah seeder secara berurutan:

| Seeder | Keterangan |
|--------|------------|
| `UserSeeder` | Membuat 1 Admin dan 3 Resepsionis (1 non-aktif) |
| `VisitorSeeder` | Membuat 20 data tamu dummy menggunakan Faker dengan locale id_ID (Indonesia) |
| `FeedbackSeeder` | Membuat feedback untuk tamu yang berstatus "selesai", terhubung via `visitor_id` |

Jika ingin menjalankan seeder secara terpisah:

```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=VisitorSeeder
php artisan db:seed --class=FeedbackSeeder
```

Jika ingin reset seluruh database dan isi ulang data:

```bash
php artisan migrate:refresh --seed
```

### 6. Jalankan Server (Development)

```bash
php artisan serve
```

API akan berjalan di `http://localhost:8000`.

### 7. Build Produksi — Single URL (Opsional)

Untuk menjalankan frontend dan backend dalam **1 URL**, build Vue terlebih dahulu:

```bash
cd ../frontend-butaka
npm run build
```

Hasil build otomatis masuk ke `backend-butaka/public/`. Sekarang jalankan Laravel saja:

```bash
cd ../backend-butaka
php artisan serve
```

Akses `http://localhost:8000` — Vue SPA + API dalam 1 URL.

**Catatan:**
- File Laravel (`index.php`, `.htaccess`) tidak akan terhapus saat build.
- Route `/{any}` di `web.php` akan mengarahkan semua URL non-API ke Vue SPA.
- CORS tidak diperlukan lagi karena sudah 1 domain.

---

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@butaka.com | password |
| Resepsionis 1 | resepsionis1@butaka.com | password |
| Resepsionis 2 | resepsionis2@butaka.com | password |

---

## API Endpoints

### Public Routes (Tanpa Login)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth/login` | Login dan dapatkan token |
| POST | `/api/visitors` | Self check-in tamu |
| POST | `/api/feedback` | Submit feedback |
| GET | `/api/visitors/list` | Daftar nama tamu (untuk dropdown feedback) |
| GET | `/api/visitors/recent` | Pengunjung terbaru (untuk landing page) |

### Protected Routes - Auth Management

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth/logout` | Logout |
| GET | `/api/auth/me` | Profile user saat ini |
| PUT | `/api/auth/profile` | Update profil |
| POST | `/api/auth/change-password` | Ganti password |
| POST | `/api/auth/upload-avatar` | Upload foto profil |
| POST | `/api/auth/register` | Registrasi user baru (Admin only) |

### Protected Routes - Visitor Management

| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| GET | `/api/visitors` | Staff | List semua pengunjung |
| GET | `/api/visitors/{id}` | Staff | Detail pengunjung |
| PUT | `/api/visitors/{id}` | Staff | Update data pengunjung |
| PUT | `/api/visitors/{id}/status` | Staff | Update status pengunjung |
| POST | `/api/visitors/{id}/checkout` | Staff | Check-out pengunjung |
| DELETE | `/api/visitors/{id}` | Admin | Hapus data pengunjung |

### Protected Routes - Feedback (Admin Only)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/feedback` | List semua feedback |
| GET | `/api/feedback/{id}` | Detail feedback |
| DELETE | `/api/feedback/{id}` | Hapus feedback |

### Protected Routes - Dashboard (Admin Only)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/dashboard/stats` | Statistik umum |
| GET | `/api/dashboard/recent-visitors` | 5 pengunjung terakhir |
| GET | `/api/dashboard/rating-breakdown` | Distribusi rating |
| GET | `/api/dashboard/visitor-trends` | Tren pengunjung 7 hari |

### Protected Routes - User Management (Admin Only)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/users` | List semua user |
| POST | `/api/users` | Buat user baru |
| GET | `/api/users/{id}` | Detail user |
| PUT | `/api/users/{id}` | Update user |
| DELETE | `/api/users/{id}` | Hapus user |

---

## Struktur Direktori Penting

```
backend-butaka/
├── app/
│   ├── Http/Controllers/Api/     # Controller untuk setiap fitur API
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── VisitorController.php
│   │   ├── FeedbackController.php
│   │   └── DashboardController.php
│   └── Models/                   # Definisi model database
│       ├── User.php
│       ├── Visitor.php
│       └── Feedback.php
├── database/
│   ├── migrations/               # Skrip pembuatan tabel
│   └── seeders/                  # Data dummy untuk pengujian
│       ├── UserSeeder.php
│       ├── VisitorSeeder.php     # Faker id_ID, 20 data acak
│       └── FeedbackSeeder.php    # Relasi ke visitor_id
├── routes/
│   ├── api.php                   # Definisi seluruh endpoint API
│   └── web.php                   # Catch-all route untuk Vue SPA
├── config/
│   └── cors.php                  # Konfigurasi CORS (dev & production)
├── public/                       # Document root (+ hasil build Vue)
│   ├── index.php                 # Entry point Laravel
│   ├── index.html                # Entry point Vue SPA (hasil build)
│   └── assets/                   # Bundle JS/CSS Vue (hasil build)
├── docker/                       # Konfigurasi Docker
│   ├── nginx.conf
│   ├── supervisord.conf
│   └── entrypoint.sh
├── Dockerfile                    # Multi-stage Docker build
└── .env.example                  # Template konfigurasi environment
```

---

## Docker (Deployment)

Proyek ini sudah dilengkapi `Dockerfile` multi-stage untuk deployment ke platform seperti Render.com.

### Build Docker Image

```bash
docker build -t butaka-backend .
```

### Jalankan Container

```bash
docker run -p 80:80 --env-file .env butaka-backend
```

Pastikan variabel environment berikut sudah dikonfigurasi untuk production:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generated-key>
DB_CONNECTION=mysql
DB_HOST=<host>
DB_PORT=3306
DB_DATABASE=<database>
DB_USERNAME=<username>
DB_PASSWORD=<password>
```

---

## Skema Database

### Tabel `users`
- id, name, avatar, email, password, role (admin/resepsionis), is_active

### Tabel `visitors`
- id, name, phone, email, purpose, host_name, institution, status (menunggu/berkunjung/selesai), check_in_time, check_out_time

### Tabel `feedback`
- id, visitor_id (FK ke visitors), rating (1-5), comment, created_at

---

## License

2025 BuTaKa - Project Magang
