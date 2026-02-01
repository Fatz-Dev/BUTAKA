## 📋 **Endpoint API yang disediakan**

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