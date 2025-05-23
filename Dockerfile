FROM php:8.3-fpm

# Install extensions
RUN apt-get update && apt-get install -y \
    unzip zip git libzip-dev libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY .env.example .env

# Hapus symlink public/storage jika sudah ada (misal dari volume)
RUN rm -rf /var/www/html/public/storage


COPY src/ .


# Buat ulang symlink storage
RUN php artisan storage:link

# Copy semua file Laravel ke container

COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --verbose

# Optional: set permissions
RUN chown -R www-data:www-data /var/www/html