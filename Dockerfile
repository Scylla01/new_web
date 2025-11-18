# Stage 1: Cài vendor bằng Composer
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# Stage 2: Image chạy Laravel
FROM php:8.2-cli

# Cài các thư viện & extension PHP cần cho Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy code và vendor
COPY . /app
COPY --from=vendor /app/vendor /app/vendor

# Phân quyền
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Render sẽ set PORT
ENV PORT=8000
EXPOSE 8000

# Chỉ chạy server, KHÔNG migrate ở đây
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
