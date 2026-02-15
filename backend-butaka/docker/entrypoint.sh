#!/bin/bash
set -e

echo "🚀 Starting BUTAKA Backend..."

# ============================================
# 1. Handle Render.com PORT environment variable
# ============================================
if [ ! -z "$PORT" ]; then
    echo "📌 Configuring Nginx to listen on port $PORT"
    sed -i "s/listen 80/listen $PORT/" /etc/nginx/http.d/default.conf
fi

# ============================================
# 2. Setup SQLite database (if using SQLite)
# ============================================
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    DB_PATH="/var/www/html/database/database.sqlite"
    if [ ! -f "$DB_PATH" ]; then
        echo "📂 Creating SQLite database..."
        touch "$DB_PATH"
        chown www-data:www-data "$DB_PATH"
        chmod 664 "$DB_PATH"
    fi
fi

# ============================================
# 3. Ensure storage directories exist
# ============================================
echo "📁 Ensuring storage directories..."
mkdir -p storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache/data \
         storage/logs \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache database

# ============================================
# 4. Laravel optimization for production
# ============================================
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ============================================
# 5. Run database migrations
# ============================================
echo "🗃️ Running database migrations..."
php artisan migrate --force

# ============================================
# 6. Generate app key if not set
# ============================================
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# ============================================
# 7. Create storage link
# ============================================
echo "🔗 Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "✅ Setup complete! Starting services..."

# ============================================
# 8. Start Supervisor (manages PHP-FPM + Nginx)
# ============================================
exec /usr/bin/supervisord -c /etc/supervisord.conf
