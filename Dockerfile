FROM php:8.2-apache

# Cài đặt các thư viện hệ thống và các PHP extensions cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Bật module mod_rewrite của Apache để xử lý định tuyến (routing) của Laravel
RUN a2enmod rewrite

# Cấu hình Apache DocumentRoot trỏ thẳng vào thư mục public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Thiết lập thư mục làm việc trong container
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn vào trong container
COPY . .

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cài đặt các thư viện PHP (dependencies) của Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Phân quyền cho thư mục storage và bootstrap/cache của Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mở cổng 80 cho web server
EXPOSE 80

# Chạy Apache ở chế độ foreground
CMD ["apache2-foreground"]
