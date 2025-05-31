FROM php:8.3-fpm

# Install extensions
RUN apt-get update && apt-get install -y \
    unzip zip git libzip-dev libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    netcat-traditional && \
    docker-php-ext-install pdo pdo_mysql zip 
# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# Salin semua file dari konteks build (yaitu folder 'be') ke /var/www/html
# Ini akan menyalin composer.json, artisan, .env.example, dll.
COPY . .
# COPY ./docker-entrypoint.sh /var/www/html/docker-entrypoint.sh
# Install dependencies
# Ini akan membuat folder vendor/ dan autoload.php
RUN composer install --no-dev --optimize-autoloader --verbose

# --- PERHATIAN: Lanjutkan dari sini ---

# Hapus symlink public/storage jika sudah ada (penting untuk kasus volume)
# Ini harus dilakukan SETELAH 'COPY . .' karena symlink di host bisa mengganggu build.
RUN rm -rf public/storage

# Buat ulang symlink storage
# Ini harus setelah composer install karena php artisan membutuhkan autoload.php
RUN php artisan storage:link
# RUN php artisan migrate --seed

# Optional: set permissions untuk memastikan web server bisa menulis
RUN chown -R www-data:www-data /var/www/html
RUN chmod +x ./docker-entrypoint.sh
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
# Perintah default untuk menjalankan PHP-FPM saat kontainer dimulai
CMD ["php-fpm"]