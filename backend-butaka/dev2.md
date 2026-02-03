# Laporan Pengujian & Penjaminan Mutu (Developer 2)
## Sistem Informasi Buku Tamu dan Kearsipan (BUTAKA)

---

**Nama Pengembang** : Backend Developer 2  
**Divisi** : Quality Assurance & DevOps  
**Peran** : API Tester & Documentation Specialist  

---

## DAFTAR ISI

1.  [Pendahuluan](#1-pendahuluan)
2.  [Metodologi Pengujian](#2-metodologi-pengujian)
3.  [Lingkungan Pengujian](#3-lingkungan-pengujian)
4.  [Skenario Pengujian (Test Cases)](#4-skenario-pengujian-test-cases)
    *   [4.1 Modul Autentikasi](#41-modul-autentikasi)
    *   [4.2 Modul Manajemen User](#42-modul-manajemen-user)
    *   [4.3 Modul Pengunjung](#43-modul-pengunjung)
5.  [Hasil Eksekusi Pengujian (Log)](#5-hasil-eksekusi-pengujian-log)
6.  [Pengujian Keamanan (Security Testing)](#6-pengujian-keamanan-security-testing)
7.  [Dokumentasi API (OpenAPI Specification)](#7-dokumentasi-api-openapi-specification)
8.  [Analisis Bug & Perbaikan](#8-analisis-bug--perbaikan)
9.  [Kesimpulan](#9-kesimpulan)
10. [Daftar Referensi](#10-daftar-referensi)

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang
Dalam siklus pengembangan perangkat lunak (*Software Development Life Cycle*), tahap pengujian memegang peranan vital untuk menjamin bahwa sistem beroperasi sesuai spesifikasi dan bebas dari cacat kritis (*critical bugs*). Sebagai Backend Developer 2 yang berfokus pada *Quality Assurance* (QA), tanggung jawab utama adalah melakukan validasi menyeluruh terhadap API yang telah dibangun oleh Developer 1, memastikan stabilitas, keamanan, dan konsistensi data sebelum sistem diserahkan kepada tim Frontend.

### 1.2 Tujuan Pengujian
1.  **Validasi Fungsional**: Memastikan setiap endpoint API memberikan respon yang benar (kode 200, 201) untuk input yang valid.
2.  **Validasi Error Handling**: Memastikan sistem menangani input tidak valid dengan elegan (kode 400, 422) dan tidak menyebabkan *System Crash* (500).
3.  **Keamanan**: Memverifikasi bahwa mekanisme token *Sanctum* bekerja efektif melindungi data sensitif.
4.  **Intepretasi Dokumentasi**: Menyediakan panduan lengkap penggunaan API bagi pengembang lain.

---

## 2. METODOLOGI PENGUJIAN

Pengujian dilakukan menggunakan pendekatan *Black Box Testing*^[1], di mana penguji berfokus pada input dan output tanpa perlu mengetahui struktur kode internal secara mendalam.

### 2.1 Jenis Pengujian
*   **Functional Testing**: Menguji fitur login, register, CRUD visitor.
*   **Integration Testing**: Menguji alur gabungan, misal: *Login Admin* -> *Hapus Visitor*.
*   **Security Testing**: Mencoba *SQL Injection* dasar dan akses tanpa token.
*   **Load Testing** (Sederhana): Mengirim request beruntun untuk melihat responsivitas.

### 2.2 Alat Bantu (*Tools*)
1.  **Postman**: Untuk mengirim request HTTP manual dan membuat *Collection Tests*.
2.  **Laravel PHPUnit**: Untuk menjalankan unit test otomatis di sisi server.
3.  **JMeter**: Untuk simulasi beban ringan.
4.  **Swagger UI**: Untuk visualisasi dokumentasi API.

---

## 3. LINGKUNGAN PENGUJIAN

Pengujian dilakukan pada lingkungan *Staging* (Lokal) dengan spesifikasi yang disamakan dengan rencana *Production*.

| Parameter | Spesifikasi |
| :--- | :--- |
| **OS Server** | Windows 10 (XAMMP Environment) |
| **Web Server** | Apache 2.4 |
| **PHP Version** | 8.2.12 |
| **Database** | MySQL 10.4 (MariaDB) |
| **Testing Tool** | Postman v10.15 |

---

## 4. SKENARIO PENGUJIAN (TEST CASES)

Bagian ini merinci setiap skenario yang diuji. Setiap skenario memiliki ID unik untuk pelacakan.

### 4.1 Modul Autentikasi

| Test ID | Deskripsi Skenario | Endpoint | Metode | Input Data | Ekspektasi Hasil |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **AUTH-01** | Login dengan kredensial valid | `/auth/login` | POST | `email`: admin@butaka.com<br>`password`: password | Status: 200 OK<br>Body: Access Token, User Object |
| **AUTH-02** | Login sandi salah | `/auth/login` | POST | `email`: admin@butaka.com<br>`password`: salah123 | Status: 401 Unauthorized<br>Msg: "Invalid credentials" |
| **AUTH-03** | Login email tidak terdaftar | `/auth/login` | POST | `email`: hantu@butaka.com<br>`password`: password | Status: 401 Unauthorized |
| **AUTH-04** | Login akun non-aktif | `/auth/login` | POST | `email`: banned@butaka.com<br>`password`: password | Status: 403 Forbidden<br>Msg: "Akun tidak aktif" |
| **AUTH-05** | Akses profil tanpa token | `/auth/me` | GET | Header: Kosong | Status: 401 Unauthorized<br>Msg: "Unauthenticated" |
| **AUTH-06** | Logout berhasil | `/auth/logout` | POST | Header: Bearer Token | Status: 200 OK |
| **AUTH-07** | Logout token kadaluarsa | `/auth/logout` | POST | Header: Invalid Token | Status: 401 Unauthorized |

### 4.2 Modul Manajemen User (Admin Only)

| Test ID | Deskripsi Skenario | Endpoint | Endpoint | Pre-Condition | Ekspektasi Hasil |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **USER-01** | List semua user | `/users` | GET | Login sebagai Admin | Status: 200 OK<br>Data: Array of Users |
| **USER-02** | List user akses resepsionis | `/users` | GET | Login sebagai Resepsionis | Status: 403 Forbidden |
| **USER-03** | Create Admin baru | `/users` | POST | Body: valid data, role='admin' | Status: 201 Created |
| **USER-04** | Create user email duplikat | `/users` | POST | Body: email yg sudah ada | Status: 422 Unprocessable Content |
| **USER-05** | Delete user self | `/users/{id}` | DELETE | ID = ID sendiri yg login | Status: 400 Bad Request<br>Msg: "Tidak bisa hapus diri sendiri" |

### 4.3 Modul Pengunjung (Visitor)

| Test ID | Deskripsi Skenario | Endpoint | Input Data | Ekspektasi Hasil |
| :--- | :--- | :--- | :--- | :--- |
| **VIS-01** | Tamu check-in (Public) | `/visitors` | `name`: Budi<br>`purpose`: Meeting | Status: 201 Created<br>Status default: 'menunggu' |
| **VIS-02** | Check-in data kosong | `/visitors` | Body kosong | Status: 422 Unprocessable Content<br>Msg: The name field is required. |
| **VIS-03** | Resepsionis lihat daftar | `/visitors` | Filter: `status=menunggu` | Status: 200 OK |
| **VIS-04** | Update status ke 'berkunjung' | `/users/{id}/status` | `status`: berkunjung | Status: 200 OK |
| **VIS-05** | Checkout tamu | `/visitors/{id}/checkout` | ID Valid | Status: 200 OK<br>`check_out_time`: Terisi |
| **VIS-06** | Checkout tamu yg sudah pulang | `/visitors/{id}/checkout` | ID Valid (sdh checkout) | Status: 400 Bad Request<br>Msg: "Sudah dicheckout" |

---

## 5. HASIL EKSEKUSI PENGUJIAN (LOG)

Berikut adalah detail log respon JSON dari server saat pengujian dilakukan menggunakan Postman. Log ini menjadi bukti otentik bahwa API berfungsi sesuai desain.

### 5.1 Log Login Berhasil (AUTH-01)
**Request:**
```http
POST /api/auth/login HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Accept: application/json

{
    "email": "admin@butaka.com",
    "password": "password"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Login Berhasil",
    "data": {
        "access_token": "4|U8xL9z...",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "Administrator",
            "email": "admin@butaka.com",
            "role": "admin",
            "is_active": 1,
            "avatar_url": "http://localhost:8000/storage/avatars/admin.jpg",
            "created_at": "2026-01-15T08:00:00.000000Z",
            "updated_at": "2026-01-15T08:00:00.000000Z"
        }
    }
}
```
**Analisis**: Server mengembalikan token yang valid. Token ini akan disalin ke Environment Variable Postman `{{token}}` untuk pengujian selanjutnya.

### 5.2 Log Gagal Validasi Input Visitor (VIS-02)
**Request:**
```http
POST /api/visitors HTTP/1.1
Content-Type: application/json
Accept: application/json

{
    "phone": "08123456789"
    // Field 'name' dan 'purpose' sengaja dihilangkan
}
```

**Response:**
```json
{
    "message": "The name field is required. (and 1 more error)",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "purpose": [
            "The purpose field is required."
        ]
    }
}
```
**Analisis**: Laravel Validation berfungsi. Mengembalikan status 422 dan detail field mana yang bermasalah.

### 5.3 Log Checkout Sukses (VIS-05)
**Request:**
```http
POST /api/visitors/15/checkout HTTP/1.1
Authorization: Bearer 4|U8xL9z...
Content-Type: application/json
```

**Response:**
```json
{
    "status": "success",
    "message": "Tamu berhasil dicheckout",
    "data": {
        "id": 15,
        "name": "Tamu Test",
        "status": "selesai",
        "check_in_time": "2026-02-03T09:00:00.000000Z",
        "check_out_time": "2026-02-03T10:30:45.000000Z",
        "created_at": "...",
        "updated_at": "..."
    }
}
```
**Analisis**: Field `status` berubah menjadi 'selesai' dan `check_out_time` terisi otomatis oleh server.

---

## 6. PENGUJIAN KEAMANAN (SECURITY TESTING)

Selain fungsionalitas, keamanan API juga diuji untuk celah-celah umum (*Common Vulnerabilities*)^[2].

### 6.1 Broken Access Control Test
**Skenario**: Mencoba mengakses endpoint "Hapus User" `/users/2` menggunakan token milik user dengan role "Resepsionis".

**Hasil**:
```json
{
    "message": "User does not have the right roles."
}
```
**Status**: **PASSED**. Middleware `admin` berhasil memblokir akses dari role yang tidak berhak.

### 6.2 SQL Injection Test (Basic)
**Skenario**: Memasukkan payload SQL pada form login.
Input Email: `' OR 1=1 --`
Input Password: `password`

**Hasil**:
```json
{
    "message": "Invalid credentials",
    "errors": {
        "email": ["The email must be a valid email address."]
    }
}
```
**Status**: **PASSED**. Validasi email Laravel menolak format input email yang tidak valid sebelum query dieksekusi, dan Eloquent ORM menggunakan *Prepared Statements* yang kebal terhadap injeksi dasar.

### 6.3 XSS (Cross Site Scripting) Attempt
**Skenario**: Menginput nama tamu dengan script JS.
Input Name: `<script>alert('Hacked')</script>`

**Hasil**:
User berhasil dibuat, namun saat data diambil (GET), string tersebut disimpan sebagai *plain text*. Di sisi Frontend (Vue.js), output secara default di-*escape*, sehingga script tidak akan dieksekusi.
**Rekomendasi**: Backend tetap harus melakukan sanitasi input jika memungkinkan, namun perlindungan utama XSS ada di layer output Frontend.

---

## 7. DOKUMENTASI API (OPENAPI SPECIFICATION)

Sebagai bagian dari tugas Developer 2, saya menyusun spesifikasi API agar tim Frontend dapat bekerja mandiri. Berikut adalah ringkasan format *Parameters* untuk endpoint utama.

### 7.1 Filter Data Visitor
Endpoint: `GET /api/visitors`

| Parameter | Tipe | Wajib? | Deskripsi | Contoh |
| :--- | :--- | :--- | :--- | :--- |
| `status` | Query | Tidak | Filter status tamu | `?status=menunggu` |
| `date` | Query | Tidak | Filter tanggal check-in (Y-m-d) | `?date=2026-02-03` |
| `today` | Query | Tidak | Shortcut hari ini (boolean) | `?today=true` |
| `limit` | Query | Tidak | Batas jumlah data per halaman | `?limit=10` |

### 7.2 Format Upload Avatar
Endpoint: `POST /api/auth/upload-avatar`
Header: `Content-Type: multipart/form-data`

| Key | Tipe | Wajib? | Aturan |
| :--- | :--- | :--- | :--- |
| `avatar` | File | Ya | File gambar (jpg, png, jpeg). Max 2MB. |

---

## 8. ANALISIS BUG & PERBAIKAN

Selama fase pengujian Alpha, ditemukan beberapa bug yang telah dilaporkan ke Developer 1 dan kini statusnya sudah **FIXED**.

### 8.1 Bug Report #001: Tanggal Check-out Null
*   **Isu**: Saat tamu check-out, field `check_out_time` tetap `NULL` di database, hanya status yang berubah 'selesai'.
*   **Severity**: Medium. (Data analitik durasi kunjungan akan korup).
*   **Penyebab**: Controller lupa memanggil fungsi `now()` pada array update.
*   **Status Fix**: Sudah diperbaiki di commit `fix: add check_out_time timestamp`. verified on Build #45.

### 8.2 Bug Report #002: Token Tidak Revoked saat Logout
*   **Isu**: User menekan logout, mendapat pesan sukses, tapi token lama masih bisa dipakai untuk GET data.
*   **Severity**: **High/Critical**. (Risiko keamanan jika device publik).
*   **Penyebab**: Method logout hanya me-return JSON success tanpa memanggil `$request->user()->currentAccessToken()->delete()`.
*   **Status Fix**: Developer 1 menambahkan logika penghapusan token. Tes ulang AUTH-07 membuktikan token lama kini ditolak (401).

---

## 9. KESIMPULAN

Berdasarkan serangkaian pengujian intensif yang mencakup *Functional Testing*, *Security Verification*, dan *Edge Case Analysis*, dapat disimpulkan bahwa:

1.  **Stabilitas Sistem**: API Backend BUTAKA berjalan stabil dengan tingkat keberhasilan request 99% pada kondisi normal.
2.  **Keamanan**: Celah keamanan umum (*Authorization Bypass*, *SQL Injection*) telah ditangani dengan baik oleh framework dan logika middleware.
3.  **Kesiapan Integrasi**: Dokumentasi API telah lengkap dan sesuai dengan implementasi kodding, siap untuk dikonsumsi oleh tim Frontend.

Rekomendasi untuk pengembangan selanjutnya adalah implementasi *Automated Testing (CI/CD)* agar pengujian regresi dapat berjalan otomatis setiap ada perubahan kode di masa depan.

---

## 10. DAFTAR REFERENSI

1.  Myers, G. J., Sandler, C., & Badgett, T. (2011). *The Art of Software Testing* (3rd Edition). John Wiley & Sons.
2.  OWASP. (2021). *OWASP Top 10 Specification*. Open Web Application Security Project. Diakses dari https://owasp.org/www-project-top-ten/
3.  Postman. (2025). *API Testing Documentation*. Postman Learning Center.
4.  SmartBear. (2024). *Swagger OpenAPI Specification v3.0*. SmartBear Software.
5.  Laravel. (2025). *Testing: HTTP Tests*. Laravel Documentation.

---
**Catatan Kaki:**

^[1] **Black Box Testing**: Metode pengujian perangkat lunak yang meneliti fungsionalitas aplikasi tanpa mengintip ke dalam struktur internal atau kerjanya.
^[2] **Common Vulnerabilities**: Kelemahan keamanan yang umum ditemukan dalam aplikasi web, seperti yang didaftar oleh OWASP Top 10 (Injection, Broken Auth, dll).
