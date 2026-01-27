FROM composer:2.7 AS vendor
WORKDIR /app/api
COPY ./api/composer.json ./api/composer.lock ./api/artisan ./
RUN composer install \
--optimize-autoloader \
--prefer-dist \
--no-interaction \
--no-scripts

COPY api/ ./
RUN touch ./database/database.sqlite

# Final stage
FROM php:8.4-fpm-alpine AS backend
WORKDIR /var/www/html/laravel

# Install dependencies
RUN apk add --no-cache nginx supervisor
RUN apk add --no-cache libzip-dev zip unzip mariadb-dev linux-headers
RUN docker-php-ext-install  zip bcmath pcntl

# Copy backend files
COPY --from=vendor /app/api .

# Copy nginx configuration
COPY nginx.conf /etc/nginx/http.d/default.conf


# Copy supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY run.sh ./run.sh
RUN mkdir -p /var/log/supervisord
RUN mkdir -p /var/log/nginx
RUN apk add --no-cache certbot certbot-nginx

# Expose ports
EXPOSE 80
EXPOSE 433

# Start services using supervisor
CMD ["/bin/sh", "/var/www/html/laravel/run.sh"]
