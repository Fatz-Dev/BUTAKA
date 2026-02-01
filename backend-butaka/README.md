# Butaka Backend - Guest Management System

API Backend untuk sistem manajemen tamu "Butaka", dibangun menggunakan framework Laravel 12.

## 🚀 Fitur Utama
- **Autentikasi**: Sistem login aman menggunakan Laravel Sanctum.
- **Manajemen User**: CRUD user (Admin & Resepsionis) dengan kontrol aktif/non-aktif.
- **Manajemen Pengunjung (Visitor)**: Pencatatan tamu masuk (check-in), pemantauan status, dan check-out.
- **Sistem Feedback**: Pengumpulan rating dan ulasan dari pengunjung secara publik.
- **Dashboard Ringkasan**: Statistik real-time, tren pengunjung, dan breakdown rating.
- **Manajemen Profil**: Ganti password, update info profil, dan upload foto (avatar).

## 🛠️ Tech Stack
- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Database**: MySQL / MariaDB
- **Auth**: Laravel Sanctum (Token based)
- **Tools**: Composer, Artisan, XAMPP (untuk database)

## 📋 Prasyarat
- PHP >= 8.2
- Composer
- MySQL/MariaDB (disarankan menggunakan XAMPP)
- Git

## ⚙️ Cara Setup & Instalasi

### 1. Clone Repositori
```bash
git clone [repository-url]
cd backend-butaka
```

### 2. Instalasi Dependency
Gunakan composer untuk mengunduh library Laravel:
```bash
composer install
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan konfigurasi database Anda:
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

### 5. Migrasi & Seeder Database
Pastikan server database (MySQL) Anda sudah berjalan, lalu jalankan perintah berikut:
```bash
php artisan migrate --seed
```
> **Catatan**: `--seed` akan mengisi database dengan data dummy awal untuk Admin, Resepsionis, dan sampel tamu.

### 6. Jalankan Server
```bash
php artisan serve
```
API akan berjalan di `http://localhost:8000`.

## 📖 Dokumentasi API
Detail lengkap mengenai setiap endpoint, request body, dan contoh response dapat dilihat pada file:
👉 [**API_DOCUMENTATION.md**](./API_DOCUMENTATION.md)

## 📁 Struktur Direktori Penting
- `app/Http/Controllers/Api`: Logika utama untuk setiap fitur API.
- `app/Models`: Definisi skema tabel database.
- `routes/api.php`: Definisi seluruh endpoint API.
- `database/migrations`: Skrip pembuatan tabel database.
- `database/seeders`: Data sampel untuk pengujian awal.
