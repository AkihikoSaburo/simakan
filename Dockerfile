# === Stage 1: Build Frontend Assets ===
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# === Stage 2: Production PHP & Nginx Environment ===
FROM php:8.4-fpm-alpine

# 1. Install seluruh library sistem Alpine yang dibutuhkan untuk ekstensi PHP & DomPDF
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    freetype \
    freetype-dev \
    libzip \
    libzip-dev \
    libxml2-dev \
    curl-dev \
    oniguruma-dev \
    zip \
    unzip \
    fontconfig \
    ttf-dejavu \
    bash \
    mysql-client

# 2. Compile ekstensi PHP utama (Mencegah error Composer exit code 2)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        zip \
        bcmath \
        mbstring \
        xml \
        dom \
        curl

WORKDIR /var/www/html

# 3. Copy composer resmi versi 2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Copy seluruh source code aplikasi
COPY . .

# 5. Copy hasil compile Tailwind/Vite dari Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# 6. Copy custom konfigurasi server & PHP
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/uploads.ini $PHP_INI_DIR/conf.d/uploads.ini

# 7. Jalankan composer install dengan aman
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8. Setup folder internal Nginx & hak akses storage Laravel
RUN mkdir -p /run/nginx \
    && mkdir -p /var/lib/nginx/tmp/client_body \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/lib/nginx \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/lib/nginx

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

