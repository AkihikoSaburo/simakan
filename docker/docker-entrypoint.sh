#!/bin/sh
set -e

# 1. Generate key otomatis HANYA JIKA APP_KEY di .env masih kosong
if [ -z "$APP_KEY" ] && ! grep -q "APP_KEY=base64" /var/www/html/.env; then
    echo "🔑 APP_KEY kosong, meng-generate key baru otomatis..."
    php artisan key:generate --force
fi

# 2. Jalankan optimalisasi cache Laravel
echo "⚡ Mengoptimalkan cache konfigurasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Jalankan migrasi dan seeding database secara aman
echo "🗄️ Menjalankan migrasi database..."
php artisan migrate --seed --force

# 4. Oper kendali utama ke Supervisor (Nginx + PHP-FPM)
echo "🚀 Memulai web server SIMAKAN..."
exec "$@"