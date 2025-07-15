#!/bin/sh
set -e

# Run Laravel optimizations and essential commands
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force

# Clear and cache Laravel configurations
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan view:cache
php artisan config:cache

# Start supervisor
supervisord -c /etc/supervisor/conf.d/supervisord.conf
