#!/bin/sh
set -e

echo "--- Debugging run.sh ---"
echo "APP_URL: $APP_URL"
echo "SESSION_DOMAIN: $SESSION_DOMAIN"

# Run Laravel optimizations and essential commands
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify cached values
php artisan about --env
php artisan config:show app.url
php artisan config:show session.domain

# Start services
php-fpm &
nginx -g 'daemon off;'