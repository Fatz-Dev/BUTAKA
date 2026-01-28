# BuTaKa - Buku Tamu Kantor

**BuTaKa** (Buku Tamu Kantor) adalah sistem manajemen buku tamu digital untuk kantor, dibangun dengan Vue 3, TypeScript, dan Vite.

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Prasyarat](#-prasyarat)
- [Instalasi & Menjalankan Proyek](#-instalasi--menjalankan-proyek)
- [Struktur Proyek](#-struktur-proyek)
- [Halaman & Routing](#-halaman--routing)
- [API Endpoints](#-api-endpoints)
- [Konfigurasi](#-konfigurasi)
- [State Management (Pinia Stores)](#-state-management-pinia-stores)
- [User Roles](#-user-roles)
- [Testing](#-testing)
- [Build Production](#-build-production)

---

## ✨ Fitur Utama

### Untuk Tamu (Public)
- **Self Check-in**: Tamu dapat mendaftarkan kunjungan secara mandiri
- **Feedback**: Tamu dapat memberikan rating dan komentar setelah kunjungan

### Untuk Resepsionis
- **Daftar Kunjungan Real-time**: Melihat daftar tamu yang sedang berkunjung
- **Check-out Tamu**: Menandai tamu yang telah selesai berkunjung

### Untuk Admin
- **Dashboard**: Statistik kunjungan dan ringkasan sistem
- **Manajemen Resepsionis**: CRUD data resepsionis
- **Daftar Feedback**: Melihat dan mengelola feedback dari tamu
- **Log Kunjungan**: Melihat riwayat lengkap semua kunjungan

---

## 🛠 Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| Framework | Vue 3 (Composition API) |
| Build Tool | Vite 7 |
| Language | TypeScript 5.9 |
| State Management | Pinia 3 |
| Routing | Vue Router 4 |
| HTTP Client | Axios |
| UI Components | Hope UI + Tailwind CSS |
| Notifications | SweetAlert2 |
| Icons | Font Awesome 6, Lucide Vue, Material Symbols |
| Testing | Jest + Vue Test Utils |

---

## 📦 Prasyarat

Sebelum memulai, pastikan Anda memiliki:

- **Node.js** >= 18.x
- **npm** >= 9.x (atau **yarn** / **pnpm**)
- **Backend API** yang berjalan di `http://localhost:8000` (Laravel API)

---

## 🚀 Instalasi & Menjalankan Proyek

### 1. Clone Repository

```bash
git clone https://github.com/username/butaka-frontend.git
cd butaka-frontend
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Jalankan Development Server

```bash
npm run dev
```

Aplikasi akan berjalan di: **http://localhost:5173**

### 4. Login Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@butaka.com | password |
| Resepsionis | resepsionis@butaka.com | password |

> **Note**: Pastikan backend API sudah berjalan sebelum login.

---

## 📁 Struktur Proyek

```
butaka-frontend/
├── public/                     # Static assets
│   └── assets/                 # Images, CSS, JS (Hope UI)
├── src/
│   ├── components/             # Reusable components
│   │   └── layout/             # Layout components (DashboardLayout, etc.)
│   ├── router/                 # Vue Router configuration
│   │   └── index.ts
│   ├── services/               # API service layer
│   │   └── api.ts              # Axios instance & API endpoints
│   ├── stores/                 # Pinia stores
│   │   ├── auth.ts             # Authentication state
│   │   ├── feedback.ts         # Feedback management
│   │   ├── guestLogs.ts        # Guest/Visitor management
│   │   └── users.ts            # User/Receptionist management
│   ├── views/                  # Page components
│   │   ├── admin/              # Admin pages
│   │   │   ├── DashboardView.vue
│   │   │   ├── ManageReceptionistsView.vue
│   │   │   ├── CreateReceptionistView.vue
│   │   │   ├── FeedbackListView.vue
│   │   │   └── GuestLogsView.vue
│   │   ├── receptionist/       # Receptionist pages
│   │   │   └── GuestListView.vue
│   │   ├── LandingPageView.vue # Public landing page
│   │   ├── LoginView.vue       # Login page
│   │   └── LogoutView.vue      # Logout handler
│   ├── App.vue                 # Root component
│   ├── main.ts                 # Application entry point
│   └── style.css               # Global styles
├── index.html                  # HTML entry point
├── package.json                # Dependencies & scripts
├── tsconfig.json               # TypeScript configuration
├── vite.config.ts              # Vite configuration
└── jest.config.ts              # Jest test configuration
```

---

## 🗺 Halaman & Routing

| Path | Nama | Akses | Deskripsi |
|------|------|-------|-----------|
| `/` | Landing | Public | Halaman utama untuk check-in dan feedback tamu |
| `/login` | Login | Guest | Halaman login untuk admin/resepsionis |
| `/logout` | Logout | User | Handler logout |
| `/admin` | Dashboard | Admin | Dashboard statistik admin |
| `/admin/receptionists` | Manage Receptionists | Admin | Kelola data resepsionis |
| `/admin/receptionists/add` | Add Receptionist | Admin | Tambah resepsionis baru |
| `/admin/feedback` | Feedback List | Admin | Daftar feedback dari tamu |
| `/admin/logs` | Guest Logs | Admin | Log semua kunjungan |
| `/receptionist` | Guest List | Receptionist | Daftar tamu aktif |

---

## 🔌 API Endpoints

Backend API berjalan di `http://localhost:8000/api`. Referensi lengkap:

### Authentication
| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| POST | `/auth/login` | Public | Login & dapatkan token |
| POST | `/auth/logout` | User | Logout |
| GET | `/auth/me` | User | Get profile saat ini |
| PUT | `/auth/profile` | User | Update informasi profil |
| POST | `/auth/change-password` | User | Ganti password |
| POST | `/auth/register` | Admin | Registrasi user baru |

### Users (Admin Only)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/users` | List semua user |
| POST | `/users` | Buat user baru |
| GET | `/users/{id}` | Detail user |
| PUT | `/users/{id}` | Update user |
| DELETE | `/users/{id}` | Hapus user |

### Visitors
| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| POST | `/visitors` | Public | Self check-in |
| GET | `/visitors` | Staff | List pengunjung |
| GET | `/visitors/{id}` | Staff | Detail pengunjung |
| PUT | `/visitors/{id}` | Staff | Update data |
| POST | `/visitors/{id}/checkout` | Staff | Check-out pengunjung |
| DELETE | `/visitors/{id}` | Admin | Hapus data |

### Feedback
| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| POST | `/feedback` | Public | Submit feedback |
| GET | `/feedback` | Admin | List feedback |
| GET | `/feedback/{id}` | Admin | Detail feedback |
| DELETE | `/feedback/{id}` | Admin | Hapus feedback |

### Dashboard (Admin Only)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/dashboard/stats` | Statistik umum |
| GET | `/dashboard/recent-visitors` | 5 pengunjung terakhir |
| GET | `/dashboard/rating-breakdown` | Distribusi rating |
| GET | `/dashboard/visitor-trends` | Tren pengunjung 7 hari |

---

## ⚙️ Konfigurasi

### API Base URL

Edit file `src/services/api.ts`:

```typescript
const API_BASE_URL = 'http://localhost:8000/api'
```

Untuk production, ubah ke URL backend production Anda.

### Environment Variables (Opsional)

Buat file `.env` di root project:

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

---

## 🗄 State Management (Pinia Stores)

### `auth.ts`
- Login/Logout
- Profile management
- Token handling (localStorage)
- Role-based access control

### `guestLogs.ts`
- Fetch visitors
- Add guest (check-in)
- Checkout guest
- Delete guest

### `feedback.ts`
- Fetch feedback
- Add feedback
- Delete feedback

### `users.ts`
- Fetch users (admin/receptionist)
- CRUD operations for users
- Filter by role

---

## 👥 User Roles

| Role | Kode | Akses |
|------|------|-------|
| **Admin** | `admin` | Full access ke semua fitur |
| **Resepsionis** | `resepsionis` | Lihat daftar tamu, check-out tamu |

---

## 🧪 Testing

Jalankan unit tests:

```bash
npm run test
```

---

## 📦 Build Production

Build untuk production:

```bash
npm run build
```

Preview hasil build:

```bash
npm run preview
```

Output build akan tersedia di folder `dist/`.

---

## 📝 License

© 2025 BuTaKa - Project Magang

---

## 🤝 Contributing

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📧 Contact

Untuk pertanyaan atau masalah, silakan buat Issue di repository ini.
