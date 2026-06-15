#!/bin/sh

# Cache cấu hình Laravel
php artisan config:cache
php artisan route:cache

# Tự động chạy migrations khi container khởi động
echo "Running database migrations..."
php artisan migrate --force

# Tự động seed dữ liệu mẫu (Seeder của chúng ta dùng updateOrCreate nên chạy lại nhiều lần vẫn an toàn)
echo "Running database seeders..."
php artisan db:seed --force

# Khởi chạy Apache web server
echo "Starting Apache web server..."
exec apache2-foreground
