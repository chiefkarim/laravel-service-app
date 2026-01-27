#!/bin/sh
set -e
cd /var/www/html/laravel

chown -R www-data:www-data database storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
chmod 664 database/database.sqlite
php artisan storage:link || true
php artisan migrate --force

# Clear and cache Laravel configurations
php artisan optimize:clear
php artisan optimize

exec supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
