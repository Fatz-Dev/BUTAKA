# ============================================
# Stage 1: Install Composer Dependencies
# ============================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

# ============================================
# Stage 2: Production Image (PHP 8.2 + Nginx)
# ============================================
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    curl \
    bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_sqlite \
    pdo_mysql \
    bcmath \
    ctype \
    mbstring \
    xml \
    zip \
    gd \
    pcntl \
    tokenizer

# Set working directory
WORKDIR /var/www/html

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy Supervisor configuration
COPY docker/supervisord.conf /etc/supervisord.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Copy vendor from stage 1
COPY --from=vendor /app/vendor ./vendor

# Copy application code
COPY . .

# Remove unnecessary files
RUN rm -rf tests docker .git .env .env.example \
    node_modules .editorconfig .gitignore .gitattributes \
    phpunit.xml vite.config.js package.json

# Create required directories and set permissions
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    database \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

# Expose port (Render assigns PORT env variable)
EXPOSE 80

# Start application
ENTRYPOINT ["/entrypoint.sh"]
