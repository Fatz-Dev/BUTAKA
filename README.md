# BuTaKa (Buku Tamu Kantor)

Sistem Manajemen Buku Tamu Digital untuk perkantoran. Dibangun dengan **Laravel 11** (Backend) dan **Vue.js 3** (Frontend) menggunakan TypeScript.

![BuTaKa Banner](frontend-butaka/public/assets/images/logo-new2.png)

---

## 📋 Deskripsi Proyek

BuTaKa adalah aplikasi web yang memungkinkan kantor untuk mengelola kunjungan tamu secara digital. Sistem ini dilengkapi dengan:

- **Landing Page** untuk pengunjung melakukan check-in mandiri
- **Dashboard Admin** untuk monitoring dan manajemen penuh
- **Dashboard Resepsionis** untuk mengelola daftar tamu harian
- **Sistem Feedback** untuk mengumpulkan ulasan pengunjung
- **Statistik Kunjungan** untuk analisis data kunjungan

---

## 🏗️ Arsitektur Proyek

```
BUTAKA/
├── backend-butaka/      # Laravel 11 REST API
├── frontend-butaka/     # Vue.js 3 SPA
└── README.md            # Dokumentasi ini
```

### Diagram Arsitektur

```
┌─────────────────────────────────────────────────────────────┐
│                        FRONTEND                              │
│                   (Vue.js 3 + Vite)                         │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐        │
│  │ Landing │  │  Login  │  │  Admin  │  │ Recep.  │        │
│  │  Page   │  │  Page   │  │Dashboard│  │Dashboard│        │
│  └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘        │
│       │            │            │            │              │
│       └────────────┴────────────┴────────────┘              │
│                         │                                    │
│                    [ Axios API ]                            │
│                         │                                    │
└─────────────────────────┼───────────────────────────────────┘
                          │ HTTP/REST
┌─────────────────────────┼───────────────────────────────────┐
│                         │                                    │
│                    BACKEND                                   │
│               (Laravel 11 + Sanctum)                        │
│                         │                                    │
│  ┌─────────┐  ┌────────┴────────┐  ┌─────────┐             │
│  │  Auth   │  │   Controllers   │  │  Models │             │
│  │Sanctum  │  │ (API Endpoints) │  │  (ORM)  │             │
│  └────┬────┘  └────────┬────────┘  └────┬────┘             │
│       │                │                │                   │
│       └────────────────┴────────────────┘                   │
│                         │                                    │
│                    [ SQLite DB ]                            │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

---

## 🚀 Instalasi & Setup

### Prasyarat (Prerequisites)

Pastikan Anda sudah menginstall:

| Software | Versi Minimum | Keterangan |
|----------|---------------|------------|
| PHP | 8.2+ | Backend runtime |
| Composer | 2.x | PHP dependency manager |
| Node.js | 18+ | Frontend runtime |
| npm | 9+ | Node package manager |
| Git | 2.x | Version control |

### Clone Repository

```bash
# Clone repository utama
git clone https://github.com/username/BUTAKA.git

# Masuk ke direktori proyek
cd BUTAKA
```

---

## 🔧 Setup Backend (Laravel 11)

### Langkah 1: Masuk ke Direktori Backend

```bash
cd backend-butaka
```

### Langkah 2: Install Dependencies

```bash
composer install
```

### Langkah 3: Konfigurasi Environment

```bash
# Salin file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Langkah 4: Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
# Untuk SQLite (default)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Atau untuk MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=butaka
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 5: Buat Database SQLite (Jika menggunakan SQLite)

```bash
# Buat file database SQLite
touch database/database.sqlite
```

### Langkah 6: Jalankan Migrasi dan Seeder

```bash
# Jalankan migrasi untuk membuat tabel
php artisan migrate

# Jalankan seeder untuk data awal (opsional)
php artisan db:seed
```

### Langkah 7: Setup Storage Link

```bash
php artisan storage:link
```

### Langkah 8: Jalankan Server Backend

```bash
php artisan serve
```

Server backend akan berjalan di `http://127.0.0.1:8000`

---

## 🎨 Setup Frontend (Vue.js 3)

### Langkah 1: Masuk ke Direktori Frontend

```bash
# Dari root direktori BUTAKA
cd frontend-butaka
```

### Langkah 2: Install Dependencies

```bash
npm install
```

### Langkah 3: Konfigurasi API Base URL

Edit file `src/services/api.ts` jika perlu mengubah base URL:

```typescript
// Default: http://127.0.0.1:8000/api
const API_BASE_URL = 'http://127.0.0.1:8000/api'
```

### Langkah 4: Jalankan Development Server

```bash
npm run dev
```

Server frontend akan berjalan di `http://localhost:5173`

### Langkah 5: Build untuk Production (Opsional)

```bash
npm run build
```

File hasil build akan tersedia di folder `dist/`

---

## 📁 Struktur Direktori Backend

```
backend-butaka/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php      # Autentikasi
│   │   │       ├── DashboardController.php # Stats dashboard
│   │   │       ├── FeedbackController.php  # CRUD feedback
│   │   │       ├── UserController.php      # CRUD users
│   │   │       └── VisitorController.php   # CRUD visitors
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php         # Role admin
│   │   │   └── AdminOrReceptionistMiddleware.php
│   │   └── Requests/                       # Form validations
│   ├── Models/
│   │   ├── Feedback.php                    # Model feedback
│   │   ├── User.php                        # Model user
│   │   └── Visitor.php                     # Model visitor
│   ├── Providers/
│   └── Traits/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_visitors_table.php
│   │   ├── create_feedback_table.php
│   │   └── create_personal_access_tokens_table.php
│   └── seeders/
├── public/
├── resources/
├── routes/
│   ├── api.php                             # API routes
│   ├── console.php
│   └── web.php
├── storage/
├── tests/
├── .env
├── .env.example
├── composer.json
└── artisan
```

---

## 📁 Struktur Direktori Frontend

```
frontend-butaka/
├── public/
│   └── assets/
│       ├── css/                            # Hope UI CSS
│       ├── js/                             # Hope UI JS
│       └── images/                         # Static images
├── src/
│   ├── components/
│   │   └── layout/
│   │       ├── DashboardLayout.vue         # Layout utama
│   │       └── EmptyRouterView.vue         # Router wrapper
│   ├── router/
│   │   └── index.ts                        # Vue Router config
│   ├── services/
│   │   └── api.ts                          # Axios instance
│   ├── stores/
│   │   ├── auth.ts                         # Store autentikasi
│   │   ├── feedback.ts                     # Store feedback
│   │   ├── guestLogs.ts                    # Store visitor
│   │   └── users.ts                        # Store users
│   ├── views/
│   │   ├── admin/
│   │   │   ├── CreateReceptionistView.vue
│   │   │   ├── DashboardView.vue
│   │   │   ├── FeedbackListView.vue
│   │   │   ├── GuestLogsView.vue
│   │   │   └── ManageReceptionistsView.vue
│   │   ├── receptionist/
│   │   │   └── GuestListView.vue
│   │   ├── LandingPageView.vue
│   │   ├── LoginView.vue
│   │   └── LogoutView.vue
│   ├── App.vue
│   ├── main.ts
│   └── style.css
├── index.html
├── package.json
├── tsconfig.json
└── vite.config.ts
```

---

## 📊 Database Schema

### Tabel: `users`

| Kolom | Tipe | Nullable | Keterangan |
|-------|------|:--------:|------------|
| id | bigint | ❌ | Primary Key (auto) |
| name | varchar(255) | ❌ | Nama lengkap |
| email | varchar(255) | ❌ | Email (unique) |
| password | varchar(255) | ❌ | Password (hashed) |
| role | enum | ❌ | `admin` / `resepsionis` |
| avatar | varchar(255) | ✅ | Path foto profil |
| is_active | boolean | ❌ | Status aktif (default: true) |
| email_verified_at | timestamp | ✅ | Waktu verifikasi |
| remember_token | varchar(100) | ✅ | Token remember me |
| created_at | timestamp | ✅ | Waktu dibuat |
| updated_at | timestamp | ✅ | Waktu diupdate |

### Tabel: `visitors`

| Kolom | Tipe | Nullable | Keterangan |
|-------|------|:--------:|------------|
| id | bigint | ❌ | Primary Key (auto) |
| name | varchar(255) | ❌ | Nama pengunjung |
| phone | varchar(20) | ✅ | Nomor telepon |
| email | varchar(255) | ✅ | Email |
| purpose | text | ❌ | Tujuan kunjungan |
| host_name | varchar(255) | ❌ | Nama yang dikunjungi |
| institution | varchar(255) | ✅ | Instansi asal |
| status | enum | ❌ | `menunggu` / `berkunjung` / `selesai` |
| check_in_time | datetime | ❌ | Waktu check-in |
| check_out_time | datetime | ✅ | Waktu check-out |
| created_at | timestamp | ✅ | Waktu dibuat |
| updated_at | timestamp | ✅ | Waktu diupdate |

### Tabel: `feedback`

| Kolom | Tipe | Nullable | Keterangan |
|-------|------|:--------:|------------|
| id | bigint | ❌ | Primary Key (auto) |
| name | varchar(255) | ❌ | Nama pemberi feedback |
| institution | varchar(255) | ✅ | Instansi |
| rating | tinyint | ❌ | Rating 1-5 |
| comment | text | ✅ | Komentar |
| created_at | timestamp | ✅ | Waktu submit |

---

## 🔐 API Endpoints

### Public Routes (Tanpa Autentikasi)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `POST` | `/api/auth/login` | Login user |
| `POST` | `/api/visitors` | Check-in tamu baru |
| `POST` | `/api/feedback` | Submit feedback |
| `GET` | `/api/visitors/recent` | Daftar tamu terakhir |

### Protected Routes (Memerlukan Bearer Token)

#### Auth Management
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `POST` | `/api/auth/logout` | Logout user |
| `GET` | `/api/auth/me` | Get profil user |
| `PUT` | `/api/auth/profile` | Update profil |
| `POST` | `/api/auth/change-password` | Ganti password |
| `POST` | `/api/auth/upload-avatar` | Upload avatar |

#### Admin Only Routes
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `POST` | `/api/auth/register` | Registrasi user baru |
| `GET` | `/api/users` | List semua users |
| `POST` | `/api/users` | Buat user baru |
| `GET` | `/api/users/{id}` | Detail user |
| `PUT` | `/api/users/{id}` | Update user |
| `DELETE` | `/api/users/{id}` | Hapus user |
| `GET` | `/api/feedback` | List semua feedback |
| `DELETE` | `/api/feedback/{id}` | Hapus feedback |
| `GET` | `/api/dashboard/stats` | Statistik dashboard |
| `GET` | `/api/dashboard/rating-breakdown` | Breakdown rating |
| `GET` | `/api/dashboard/visitor-trends` | Tren kunjungan |
| `DELETE` | `/api/visitors/{id}` | Hapus visitor |

#### Admin & Resepsionis Routes
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/visitors` | List semua tamu |
| `GET` | `/api/visitors/{id}` | Detail tamu |
| `PUT` | `/api/visitors/{id}` | Update tamu |
| `PUT` | `/api/visitors/{id}/status` | Update status tamu |
| `POST` | `/api/visitors/{id}/checkout` | Checkout tamu |

---

## 👥 Roles & Permissions

| Fitur | Admin | Resepsionis | Public |
|-------|:-----:|:-----------:|:------:|
| Login | ✅ | ✅ | ❌ |
| Dashboard Stats | ✅ | ❌ | ❌ |
| Manage Users | ✅ | ❌ | ❌ |
| View Feedback | ✅ | ❌ | ❌ |
| Delete Feedback | ✅ | ❌ | ❌ |
| View Visitors | ✅ | ✅ | ❌ |
| Update Visitor Status | ✅ | ✅ | ❌ |
| Delete Visitors | ✅ | ❌ | ❌ |
| Check-in (Landing) | ❌ | ❌ | ✅ |
| Submit Feedback | ❌ | ❌ | ✅ |

---

## 🧪 Testing

### Backend Testing

```bash
cd backend-butaka

# Jalankan semua tests
php artisan test

# Jalankan test spesifik
php artisan test --filter=AuthTest
```

### Frontend Testing

```bash
cd frontend-butaka

# Jalankan tests (jika ada)
npm run test
```

---

## 🔧 Troubleshooting

### Error: CORS Policy

Pastikan backend sudah mengkonfigurasi CORS dengan benar di `config/cors.php`:

```php
'allowed_origins' => ['http://localhost:5173'],
'supports_credentials' => true,
```

### Error: 401 Unauthorized

1. Pastikan token bearer dikirim di header request
2. Cek apakah token masih valid
3. Pastikan user memiliki role yang sesuai

### Error: Database Connection

1. Cek konfigurasi di file `.env`
2. Pastikan file `database.sqlite` sudah ada (jika menggunakan SQLite)
3. Jalankan `php artisan config:clear`

### Error: npm install gagal

```bash
# Hapus node_modules dan cache
rm -rf node_modules
rm package-lock.json
npm cache clean --force
npm install
```

---

## 📦 Tech Stack

### Backend
- **Framework:** Laravel 11
- **Authentication:** Laravel Sanctum
- **Database:** SQLite / MySQL
- **PHP Version:** 8.2+

### Frontend
- **Framework:** Vue.js 3 (Composition API)
- **Build Tool:** Vite
- **Language:** TypeScript
- **State Management:** Pinia
- **Routing:** Vue Router 4
- **HTTP Client:** Axios
- **UI Framework:** Hope UI (Bootstrap 5)
- **Icons:** Font Awesome 6

---

## 📄 Lisensi

© 2026 BuTaKa - Buku Tamu Kantor

Dibuat dengan ❤️ untuk kemudahan manajemen tamu kantor.

---

## 👨‍💻 Kontributor

- **Developer:** [Nama Developer]
- **Design:** Hope UI by IQONIC Design

---

## 📞 Kontak & Support

Jika ada pertanyaan atau masalah, silakan hubungi:

- **Email:** support@butaka.id
- **GitHub Issues:** [Link ke Issues]