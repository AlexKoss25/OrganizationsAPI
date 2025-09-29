#!/bin/sh

# Копируем .env если нет
if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
fi

# Генерация ключа, если нет
grep -q '^APP_KEY=' /var/www/.env || php artisan key:generate

# Создаем необходимые папки и права
mkdir -p /var/www/storage /var/www/storage/logs /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Чистим и кешируем конфиги
php artisan config:clear
php artisan cache:clear
php artisan config:cache

exec "$@"
