#!/bin/sh
set -e

php artisan storage:link
php artisan migrate --force

# Only seed if needed
if [ "$(php artisan tinker --execute='echo \App\Models\User::count();')" -eq 0 ]; then
    php artisan db:seed --force
fi

php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan view:cache
php artisan config:cache

supervisord -c /etc/supervisor/conf.d/supervisord.conf
