# Panduan Deployment Produksi - Backend Butaka

Dokumen ini berisi langkah-langkah untuk melakukan deployment **Backend Butaka** (Laravel) ke lingkungan produksi.

## 1. Persyaratan Server

Pastikan server Anda memenuhi persyaratan berikut:
- **PHP 8.2** atau lebih tinggi
- **Ekstensi PHP**: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `gd`, `hash`, `iconv`, `json`, `libxml`, `mbstring`, `openssl`, `pcre`, `pdo`, `pdo_mysql` (atau database lain), `session`, `tokenizer`, `xml`, `xmlreader`, `xmlwriter`, `zip`.
- **Composer 2.x**
- **Web Server**: Nginx (direkomendasikan) atau Apache
- **Database**: MySQL 8.0+, PostgreSQL, atau MariaDB

---

## 2. Langkah-langkah Deployment

Jalankan perintah berikut di server produksi:

### A. Clone & Install Dependensi
```bash
# Clone repository (jika belum)
git clone <repository-url> backend-butaka
cd backend-butaka

# Install dependensi PHP untuk produksi
composer install --no-dev --optimize-autoloader
```

### B. Konfigurasi Environment
```bash
# Salin file .env.example
cp .env.example .env

# Generate Application Key
php artisan key:generate --ansi

# Edit file .env untuk pengaturan produksi
nano .env
```
Pastikan pengaturan berikut diubah di `.env`:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://api.yourdomain.com`
- `DB_CONNECTION=mysql` (Sesuaikan dengan DB Anda)
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (Isi sesuai kredensial server)

### C. Database & Migrasi
```bash
# Jalankan migrasi database
php artisan migrate --force
```

### D. Izin Direktori (Permissions)
Berikan izin baca/tulis ke direktori `storage` dan `bootstrap/cache`:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### E. Optimasi Performa
Jalankan perintah ini setiap kali melakukan update kode:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 3. Konfigurasi Nginx

Berikut adalah contoh konfigurasi Nginx untuk Laravel:

```nginx
server {
    listen 80;
    server_name api.yourdomain.com;
    root /var/www/backend-butaka/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Keamanan (SSL)

Gunakan **Certbot** untuk mendapatkan sertifikat SSL gratis dari Let's Encrypt:
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d api.yourdomain.com
```

---

## 5. Queue & Task Scheduling (Opsional)

Jika aplikasi menggunakan Queue (seperti yang terlihat di `.env`):
```bash
# Jalankan queue worker menggunakan Supervisor
# Referensi: https://laravel.com/docs/11.x/queues#supervisor-configuration
```

Untuk Task Scheduling, tambahkan ke crontab server:
```bash
* * * * * cd /var/www/backend-butaka && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Update Aplikasi (Masa Mendatang)
Setiap ada perubahan kode di repository:
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 7. Deployment ke Render.com (PaaS)

Render.com adalah platform PaaS yang memudahkan deployment aplikasi modern. Karena Render tidak memiliki runtime PHP native, kita menggunakan **Docker** yang sudah dikonfigurasi (`Dockerfile`).

### A. Persiapan File
Pastikan file-file berikut sudah ada di repository Anda (sudah disiapkan):
1. `Dockerfile` (Multi-stage build PHP 8.2 + Nginx)
2. `docker/nginx.conf` (Konfigurasi Nginx)
3. `docker/entrypoint.sh` (Startup script)
4. `render.yaml` (Blueprint deployment)

### B. Deployment Otomatis (Blueprint)
Cara termudah deploy ke Render:
1. Push semua perubahan ke GitHub.
2. Buka dashboard [Render.com](https://dashboard.render.com).
3. Pilih **New +** -> **Blueprint**.
4. Hubungkan repository `backend-butaka`.
5. Render akan otomatis membaca `render.yaml` dan menyiapkan service.
6. Klik **Apply** / **Create Service**.

### C. Deployment Manual (Web Service)
Jika tidak menggunakan Blueprint:
1. Pilih **New +** -> **Web Service**.
2. Connect repository GitHub Anda.
3. Pilih Runtime: **Docker**.
4. Set Environment Variables wajib:
   - `APP_KEY`: (Generate via `php artisan key:generate --show`)
   - `APP_DEBUG`: `false`
   - `APP_ENV`: `production`
   - `APP_URL`: `https://nama-app.onrender.com`
   - `FRONTEND_URL`: URL aplikasi frontend Anda (untuk CORS)
   - `DB_CONNECTION`: `sqlite` (atau `pgsql` jika pakai database eksternal)

### D. Catatan Penting
- **Database SQLite**: Di Render, filesystem bersifat *ephemeral*. Data SQLite akan **hilang** setiap kali deploy ulang / restart. Untuk production, sangat disarankan menggunakan **PostgreSQL** (Render menyediakan Managed PostgreSQL gratis 90 hari).
- **Cold Start**: Pada paket Free, service akan "tidur" setelah 15 menit inaktif. Request pertama akan memakan waktu ~50 detik untuk bangun.
