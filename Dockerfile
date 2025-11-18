# Image PHP 8.2 dùng để chạy Laravel
FROM php:8.2-cli

# Cài các thư viện & extension PHP cần thiết (có cả PostgreSQL)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Thư mục làm việc trong container
WORKDIR /app

# Copy toàn bộ source code vào container
COPY . /app

# Cài Composer (tải trực tiếp từ composer.org)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Cài vendor cho Laravel (không chạy scripts để tránh lỗi artisan khi build)
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# Phân quyền cho storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# PORT do Render truyền vào
ENV PORT=8000
EXPOSE 8000

# Chạy Laravel bằng php artisan serve
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
