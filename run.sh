#!/bin/sh
php artisan config:cache
php artisan route:cache
php artisan view:cache
php-fpm &
nginx -g 'daemon off;'