#!/bin/sh
set -e

echo "--- Debugging run.sh ---"
echo "APP_URL: $APP_URL"
echo "SESSION_DOMAIN: $SESSION_DOMAIN"

php artisan about --env
php artisan config:show app.url
php artisan config:show session.domain

php artisan config:cache
php artisan route:cache
php artisan view:cache

php-fpm &
nginx -g 'daemon off;'