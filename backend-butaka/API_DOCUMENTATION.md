# Butaka API Documentation

Dokumentasi komprehensif untuk API Proyek Butaka. Semua response dalam format JSON dengan struktur standar.

## Base URL
`http://localhost:8000/api`

## Struktur Response Standar
| Field | Type | Deskripsi |
|---|---|---|
| `success` | boolean | Menandakan apakah request berhasil |
| `message` | string | Pesan informasi dari server |
| `data` | mixed | Data utama hasil request |

---

## 📋 **Endpoint Summary Table**

| No | Kategori | Method | Endpoint | Akses | Deskripsi |
|---|---|---|---|---|---|
| 1 | **Auth** | POST | `/auth/login` | Public | Login & Dapatkan Token |
| 2 | | POST | `/auth/logout` | User | Logout & Hapus Token |
| 3 | | GET | `/auth/me` | User | Get Profile Saat Ini |
| 4 | | PUT | `/auth/profile` | User | Update Informasi Profil |
| 5 | | POST | `/auth/change-password` | User | Ganti Password |
| 6 | | POST | `/auth/upload-avatar` | User | Upload Foto Profil |
| 7 | | POST | `/auth/register` | Admin | Registrasi User Baru |
| 8 | **Users** | GET | `/users` | Admin | List Semua User |
| 9 | | POST | `/users` | Admin | Buat User Baru |
| 10 | | GET | `/users/{id}` | Admin | Detail User Tertentu |
| 11 | | PUT | `/users/{id}` | Admin | Update Data User |
| 12 | | DELETE | `/users/{id}` | Admin | Hapus User |
| 13 | **Visitors**| POST | `/visitors` | Public | Visitor Self Check-in |
| 14 | | GET | `/visitors` | Staff | List Semua Pengunjung |
| 15 | | GET | `/visitors/{id}` | Staff | Detail Pengunjung |
| 16 | | PUT | `/visitors/{id}` | Staff | Update Data Pengunjung |
| 17 | | PUT | `/visitors/{id}/status` | Staff | Update Status Kunjungan |
| 18 | | POST | `/visitors/{id}/checkout`| Staff | Check-out Pengunjung |
| 19 | | DELETE | `/visitors/{id}` | Admin | Hapus Data Pengunjung |
| 20 | **Feedback**| POST | `/feedback` | Public | Submit Feedback Tamu |
| 21 | | GET | `/feedback` | Admin | List Semua Feedback |
| 22 | | GET | `/feedback/{id}` | Admin | Detail Feedback |
| 23 | | DELETE | `/feedback/{id}` | Admin | Hapus Feedback |
| 24 | **Dash** | GET | `/dashboard/stats` | Admin | Statistik Umum |
| 25 | | GET | `/dashboard/recent-visitors`| Admin | 5 Pengunjung Terakhir |
| 26 | | GET | `/dashboard/rating-breakdown`| Admin | Distribusi Rating |
| 27 | | GET | `/dashboard/visitor-trends` | Admin | Tren Pengunjung (7 hari) |

---

## 🔐 **Authentication Endpoints**

### **1. POST /auth/login**
**Tujuan**: Login ke sistem dan mendapatkan API token.

**Method**: `POST`  
**Auth Required**: ❌ No  
**Role**: Semua Role (Admin/Receptionist)

**Request Body**:
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "access_token": "1|xyz...",
    "token_type": "Bearer",
    "user": { "id": 1, "name": "Admin", "email": "admin@example.com", "role": "admin" }
  }
}
```

**Fungsi**:
- Memvalidasi kredensial email & password
- Membuat token akses menggunakan Sanctum
- Mengembalikan info user untuk state management frontend

---

### **2. POST /auth/logout**
**Tujuan**: Menghapus token akses yang sedang digunakan.

**Method**: `POST`  
**Auth Required**: ✅ Yes  
**Role**: User Login

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

**Fungsi**:
- Menghapus current access token dari database
- Mengakhiri session API

---

### **3. GET /auth/me**
**Tujuan**: Mendapatkan data profil user yang sedang login.

**Method**: `GET`  
**Auth Required**: ✅ Yes

**Response (200 OK)**:
```json
{
  "success": true,
  "data": { "id": 1, "name": "Admin", "email": "admin@example.com", "role": "admin", ... }
}
```

**Fungsi**:
- Mengambil detail user berdasarkan token
- Digunakan frontend untuk inisialisasi user state

---

### **4. PUT /auth/profile**
**Tujuan**: Memperbarui data diri user (nama/email).

**Method**: `PUT`  
**Auth Required**: ✅ Yes

**Request Body**:
```json
{
  "name": "Admin Updated",
  "email": "newadmin@example.com"
}
```

**Fungsi**:
- Update nama atau email user yang sedang login
- Memastikan email tetap unik jika diubah

---

### **5. POST /auth/change-password**
**Tujuan**: Mengganti password user.

**Method**: `POST`  
**Auth Required**: ✅ Yes

**Request Body**:
```json
{
  "current_password": "passwordold",
  "new_password": "passwordnew123",
  "new_password_confirmation": "passwordnew123"
}
```

**Fungsi**:
- Verifikasi password lama
- Hash dan simpan password baru

---

### **6. POST /auth/upload-avatar**
**Tujuan**: Update foto profil user.

**Method**: `POST`  
**Auth Required**: ✅ Yes  
**Content-Type**: `multipart/form-data`

**Request Body**:
- `avatar`: [File Image, Max 2MB]

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Avatar uploaded successfully",
  "data": { "avatar_url": "http://.../storage/avatars/xxx.jpg" }
}
```

**Fungsi**:
- Menyimpan file gambar ke storage public
- Menghapus avatar lama jika ada
- Mengupdate path avatar di database

---

### **7. POST /auth/register**
**Tujuan**: Registrasi user baru oleh Admin.

**Method**: `POST`  
**Auth Required**: ✅ Yes  
**Role**: Admin

**Request Body**:
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "resepsionis"
}
```

**Fungsi**:
- Membuat akun admin atau resepsionis baru
- Hanya bisa dilakukan oleh user dengan role admin

---

## 👥 **User Management Endpoints**

### **8. GET /users**
**Tujuan**: List semua user (Admin & Resepsionis).

**Method**: `GET`  
**Auth Required**: ✅ Yes  
**Role**: Admin

**Fungsi**: Menampilkan daftar seluruh staf sistem dengan urutan terbaru.

---

### **9. POST /users**
**Tujuan**: Alias dari register untuk manajemen user.

**Fungsi**: Sama seperti endpoint #7.

---

### **10. GET /users/{id}**
**Tujuan**: Ambil detail data user tertentu.

**Method**: `GET`  
**Auth Required**: ✅ Yes  
**Role**: Admin

---

### **11. PUT /users/{id}**
**Tujuan**: Update data user oleh admin.

**Method**: `PUT`  
**Auth Required**: ✅ Yes  
**Role**: Admin

**Request Body**:
```json
{
  "name": "Updated Name",
  "role": "admin",
  "is_active": false
}
```

**Fungsi**: Mengubah role user atau menonaktifkan akun.

---

### **12. DELETE /users/{id}**
**Tujuan**: Hapus akun user.

**Method**: `DELETE`  
**Auth Required**: ✅ Yes  
**Role**: Admin

**Fungsi**: Hapus data user secara permanen (Dilarang menghapus akun sendiri).

---

## 🚶 **Visitor Management Endpoints**

### **13. POST /visitors** ⚠️ **PUBLIC**
**Tujuan**: Form pendaftaran tamu (Public Self Check-in).

**Method**: `POST`  
**Auth Required**: ❌ No

**Request Body**:
```json
{
  "name": "Budi Santoso",
  "phone": "081234567890",
  "purpose": "Meeting",
  "host_name": "Pak Ahmad",
  "institution": "PT Maju Jaya"
}
```

**Fungsi**:
- Pendaftaran tamu di lobby (tanpa login)
- Status otomatis "menunggu" dan mencatat waktu check-in

---

### **14. GET /visitors**
**Tujuan**: List kunjungan tamu.

**Method**: `GET`  
**Auth Required**: ✅ Yes  
**Role**: Admin & Resepsionis

**Query Parameters**:
- `status` (optional): menunggu/berkunjung/selesai
- `date` (optional): YYYY-MM-DD
- `today` (optional): Filter tamu hari ini saja

---

### **15. GET /visitors/{id}**
**Tujuan**: Detail informasi kunjungan tamu.

**Method**: `GET`  
**Auth Required**: ✅ Yes

---

### **16. PUT /visitors/{id}**
**Tujuan**: Edit data tamu yang sudah terdaftar.

**Method**: `PUT`  
**Auth Required**: ✅ Yes  
**Role**: Admin & Resepsionis

---

### **17. PUT /visitors/{id}/status**
**Tujuan**: Update status progres kunjungan.

**Method**: `PUT`  
**Auth Required**: ✅ Yes  
**Role**: Admin & Resepsionis

**Request Body**:
```json
{ "status": "berkunjung" }
```

**Fungsi**: Mengubah status dari "menunggu" -> "berkunjung" saat tamu dipanggil.

---

### **18. POST /visitors/{id}/checkout**
**Tujuan**: Proses check-out tamu saat meninggalkan lokasi.

**Method**: `POST`  
**Auth Required**: ✅ Yes  
**Role**: Admin & Resepsionis

**Response**:
```json
{
  "success": true,
  "message": "Visitor checked out successfully",
  "data": { "status": "selesai", "check_out_time": "..." }
}
```

**Fungsi**: Otomatis mengisi waktu keluar dan mengubah status menjadi "selesai".

---

### **19. DELETE /visitors/{id}**
**Tujuan**: Menghapus data kunjungan tamu.

**Method**: `DELETE`  
**Auth Required**: ✅ Yes  
**Role**: Admin

---

## 💬 **Feedback Endpoints**

### **20. POST /feedback** ⚠️ **PUBLIC**
**Tujuan**: Pengisian survei kepuasan pelanggan/tamu.

**Method**: `POST`  
**Auth Required**: ❌ No

**Request Body**:
```json
{
  "name": "Budi Santoso",
  "email": "budi@email.com",
  "message": "Pelayanan oke",
  "rating": 5
}
```

---

### **21. GET /feedback**
**Tujuan**: List semua masukan tamu untuk Admin.

**Method**: `GET`  
**Auth Required**: ✅ Yes  
**Role**: Admin

---

### **22. GET /feedback/{id}**
**Tujuan**: Detail masukan tamu.

---

### **23. DELETE /feedback/{id}**
**Tujuan**: Hapus feedback tamu.

---

## 📊 **Dashboard Endpoints (Admin)**

### **24. GET /dashboard/stats**
**Tujuan**: Ambil ringkasan statistik Dashboard.

**Response**:
```json
{
  "success": true,
  "data": {
    "total_users": 5,
    "total_visitors_today": 10,
    "current_visitors": 2,
    "avg_rating": 4.5,
    ...
  }
}
```

---

### **25. GET /dashboard/recent-visitors**
**Tujuan**: 5 data kunjungan terbaru untuk tabel dashboard.

---

### **26. GET /dashboard/rating-breakdown**
**Tujuan**: Data distribusi rating untuk grafik/chart.

**Response**:
```json
{
  "success": true,
  "data": [ { "rating": 5, "count": 12 }, ... ]
}
```

---

### **27. GET /dashboard/visitor-trends**
**Tujuan**: Data tren kunjungan 7 hari terakhir.

**Response**:
```json
{
  "success": true,
  "data": [ { "date": "2024-01-01", "total": 15 }, ... ]
}
```

---
