# Laporan Implementasi Backend (Developer 1)
## Sistem Informasi Buku Tamu dan Kearsipan (BUTAKA)

---

**Nama Pengembang** : Backend Developer 1  
**Divisi** : Back-End Engineering  
**Peran** : System Architect & Core Logic Implementer  

---

## DAFTAR ISI

1.  [Pendahuluan](#1-pendahuluan)
2.  [Tinjauan Pustaka & Teknologi](#2-tinjauan-pustaka--teknologi)
3.  [Metodologi Pengembangan](#3-metodologi-pengembangan)
4.  [Analisis & Perancangan Sistem](#4-analisis--perancangan-sistem)
5.  [Implementasi Sistem](#5-implementasi-sistem)
6.  [Kesimpulan](#6-kesimpulan)
7.  [Daftar Referensi](#7-daftar-referensi)

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang Masalah
Dalam era transformasi digital, efisiensi pengelolaan data tamu dan arsip menjadi kebutuhan mendasar bagi instansi modern. Sistem pencatatan manual (buku tulis) memiliki banyak kelemahan, seperti risiko kehilangan data, sulitnya pencarian riwayat kunjungan, dan tidak adanya keamanan privasi tamu. Oleh karena itu, Back-End Developer 1 bertugas merancang arsitektur sisi server (*server-side*) yang aman, reliabel, dan skalabel untuk mendukung aplikasi BUTAKA.

### 1.2 Ruang Lingkup Tugas
Fokus utama pengerjaan meliputi pembangunan *Core System* yang mencakup manajemen basis data, autentikasi pengguna, dan penyediaan API (*Application Programming Interface*) yang akan dikonsumsi oleh sisi Frontend. Keputusan arsitektural yang diambil berfokus pada prinsip *Clean Code* dan keamanan data standar industri.

---

## 2. TINJAUAN PUSTAKA & TEKNOLOGI

### 2.1 Arsitektur RESTful API
Sistem ini mengadopsi gaya arsitektur REST (*Representational State Transfer*)^[1]. Pendekatan ini dipilih karena sifatnya yang *stateless*, artinya setiap permintaan HTTP dari klien ke server harus memuat semua informasi yang diperlukan untuk memahami permintaan tersebut, tanpa bergantung pada konteks sesi yang disimpan di server. Hal ini sangat krusial untuk skalabilitas aplikasi.

### 2.2 Laravel Framework
Pemilihan Laravel 12 sebagai kerangka kerja didasarkan pada ekosistemnya yang matang dan fitur keamanan bawaan. Laravel menyediakan *Eloquent ORM* (*Object-Relational Mapping*)^[2] yang memudahkan interaksi dengan database menggunakan sintaks berorientasi objek, mengurangi risiko *SQL Injection* secara signifikan dibandingkan *native query*.

### 2.3 Token-Based Authentication
Untuk keamanan akses, sistem menggunakan mekanisme *Token-Based Authentication* via Laravel Sanctum. Berbeda dengan *Session-Based* tradisional, token (seperti Bearer Token) memungkinkan autentikasi yang aman lintas platform (Web, Mobile, IoT) tanpa membebani memori server untuk menyimpan state session^[3].

---

## 3. METODOLOGI PENGEMBANGAN

Pengembangan dilakukan dengan pendekatan *Iterative Waterfall*, dimana setiap fase (Analisis, Desain, Implementasi) dilakukan secara berurutan namun memungkinkan revisi kecil.
1.  **Requirement Analysis**: Membedah kebutuhan entitas (User Admin, Resepsionis, Tamu).
2.  **Schema Design**: Merancang ERD (*Entity Relationship Diagram*) yang ternormalisasi.
3.  **API Construction**: Membangun endpoint dan logika bisnis.
4.  **Security Testing**: Validasi akses dan input.

---

## 4. ANALISIS & PERANCANGAN SISTEM

### 4.1 Perancangan Basis Data (*Database Schema*)
Keputusan struktur database diambil dengan mempertimbangkan integritas data dan kecepatan akses. Skema utama terdiri dari dua entitas besar:

#### A. Entitas Users (Pengguna Internal)
Tabel ini tidak hanya menyimpan kredensial, tetapi juga manajemen peran (*Role-Based Access Control*).

*   **Keputusan Desain**: Menggunakan tipe data `ENUM` untuk kolom `role` ('admin', 'resepsionis').
*   **Alasan**: Membatasi input hanya pada nilai yang telah didefinisikan sistem, mencegah kesalahan input atau manipulasi hak akses ilegal^[4].
*   **Indeks**: Kolom `email` diberi indeks `unique` untuk mempercepat proses pencarian saat login dan mencegah duplikasi akun.

#### B. Entitas Visitors (Buku Tamu)
Tabel ini didesain untuk mencatat siklus hidup (*lifecycle*) kunjungan tamu mulai dari datang hingga pulang.

*   **Keputusan Desain**: Menggunakan *Timestamp* presisi untuk `check_in_time` dan `check_out_time`.
*   **Alasan**: Memungkinkan kalkulasi durasi kunjungan secara akurat untuk kebutuhan pelaporan analitik di masa depan.
*   **Status Flow**: Kolom `status` mengatur logika bisnis: *Menunggu* $\rightarrow$ *Berkunjung* $\rightarrow$ *Selesai*.

---

## 5. IMPLEMENTASI SISTEM

### 5.1 Implementasi Autentikasi (Security Layer)
Keamanan adalah prioritas utama. Implementasi login tidak sekadar mencocokkan password, tetapi melalui beberapa lapisan validasi.

> **Logika Bisnis:**
> Sistem pertama-tama memvalidasi format email dan password. Setelah itu, sistem akan memeriksa apakah akun tersebut berstatus "aktif". Jika password benar namun akun non-aktif (misal: pegawai yang sudah *resign*), akses tetap ditolak. Hal ini menerapkan prinsip *Defense in Depth*^[5].

Potongan logika pada `AuthController` menunjukkan bagaimana token diterbitkan hanya jika semua syarat terpenuhi:

```php
// Contoh validasi berlapis (Pseudocode Logic)
if (PasswordCocok && AkunAktif) {
    GenerateToken();
    Return Sukses;
} else {
    Return Error 401/403;
}
```

### 5.2 Manajemen Request & Validasi Input
Untuk mencegah masuknya data sampah (*Garbage In, Garbage Out*), seluruh *endpoint* POST dan PUT dilindungi oleh *Form Request Validation*.

*   **Strict Typing**: Password wajib minimal 8 karakter.
*   **Sanitization**: Input string dibersihkan dari tag HTML untuk mencegah serangan XSS (*Cross-Site Scripting*)^[6].
*   **feedback**: Jika validasi gagal, API mengembalikan kode 422 (*Unprocessable Content*) dengan pesan error spesifik, memudahkan frontend developer memperbaiki input.

### 5.3 Penerapan Middleware
Sistem routing dikelompokkan berdasarkan hak akses menggunakan *Middleware*. Ini berfungsi sebagai "pos satpam" digital.
1.  **Public Routes**: Endpoint yang aman diakses anonim (contoh: Form Check-in Tamu).
2.  **Protected Routes**: Membutuhkan token valid.
3.  **Role Middleware**: Filter spesifik, misalnya hanya Admin yang boleh menghapus data tamu, sementara resepsionis hanya boleh melihat dan mengupdate status.

---

## 6. KESIMPULAN

Pengembangan backend sistem BUTAKA oleh Developer 1 telah menghasilkan fondasi sistem yang memenuhi standar industri modern.
1.  **Keamanan**: Implementasi Sanctum dan validasi berlapis meminimalisir risiko peretasan dasar.
2.  **Integritas Data**: Normalisasi database memastikan data konsisten dan tidak redundan.
3.  **Ketersediaan**: Arsitektur RESTful memungkinkan sistem diakses oleh berbagai platform klien di masa depan tanpa mengubah kode sisi server.

---

## 7. DAFTAR REFERENSI

1.  Fielding, R. T. (2000). *Architectural Styles and the Design of Network-based Software Architectures*. University of California, Irvine.
2.  Otwell, T. (2025). *Laravel 11 Documentation: Eloquent ORM*. Laravel LLC. Diakses dari https://laravel.com/docs/eloquent
3.  IETF. (2012). *RFC 6750 - The OAuth 2.0 Authorization Framework: Bearer Token Usage*. Internet Engineering Task Force.
4.  Date, C. J. (2003). *An Introduction to Database Systems* (8th Edition). Addison-Wesley.
5.  NIST. (2020). *Security and Privacy Controls for Information Systems and Organizations*. National Institute of Standards and Technology.
6.  OWASP. (2021). *OWASP Top Ten: Broken Access Control*. Open Web Application Security Project.

---
**Catatan Kaki (Footnotes):**

^[1] **REST (Representational State Transfer)**: Sebuah gaya arsitektur perangkat lunak untuk sistem terdistribusi seperti World Wide Web.
^[2] **ORM (Object-Relational Mapping)**: Teknik pemrograman untuk mengonversi data antara sistem tipe yang tidak kompatibel dalam bahasa pemrograman berorientasi objek.
^[3] **Stateless**: Kondisi di mana server tidak menyimpan informasi tentang sesi klien sebelumnya; setiap permintaan diproses secara independen.
^[4] **RBAC (Role-Based Access Control)**: Metode pengaturan akses sistem berdasarkan peran pengguna individu dalam perusahaan.
^[5] **Defense in Depth**: Strategi keamanan siber yang menggunakan beberapa lapisan pertahanan untuk melindungi integritas informasi.
^[6] **XSS (Cross-Site Scripting)**: Kerentanan keamanan web yang memungkinkan penyerang menyisipkan skrip sisi klien ke dalam halaman web yang dilihat oleh pengguna lain.
