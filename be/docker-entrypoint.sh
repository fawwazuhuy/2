#!/bin/sh

# Tunggu database siap
# Ini adalah bagian kritis. Gunakan tool seperti wait-for-it.sh atau implementasi sederhana.
# Contoh sederhana (tidak sekuat wait-for-it.sh):
# Loop ini akan mencoba terhubung ke database sampai berhasil
echo "Waiting for database..."
while ! nc -z mysql 3306; do
  sleep 1
done
echo "Database is ready!"

# Jalankan migrasi database
# --force diperlukan di produksi karena akan meminta konfirmasi
echo "Running database migrations..."
php artisan migrate --force

# Opsional: Jalankan seeding (hanya untuk development/testing)
# Pastikan Anda hanya melakukan ini di lingkungan yang sesuai
if [ "$APP_ENV" = "local" ] || [ "$APP_ENV" = "testing" ]; then
    echo "Running database seeding..."
    php artisan db:seed --force
fi

# Mulai PHP-FPM (perintah utama service app Anda)
echo "Starting PHP-FPM..."
exec php-fpm