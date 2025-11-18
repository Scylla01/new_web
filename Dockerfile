# Stage 1: dùng Composer để cài vendor
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist

# Stage 2: image chính chạy Laravel
FROM php:8.2-cli

# Cài các thư viện & extension PHP cần cho Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy toàn bộ code vào container
COPY . /app
# Copy thư mục vendor từ stage Composer
COPY --from=vendor /app/vendor /app/vendor

# Phân quyền cho storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Render sẽ truyền PORT vào, mình dùng cho php artisan serve
ENV PORT=8000
EXPOSE 8000

# Lệnh chạy Laravel
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
