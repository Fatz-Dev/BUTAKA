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
