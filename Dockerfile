# Frontend build stage
FROM node:22-alpine AS frontend
WORKDIR /app/client
COPY client/package.json client/pnpm-lock.yaml ./
RUN npm install -g pnpm && pnpm install
COPY client/ ./
RUN pnpm run build

# Backend build stage
FROM composer:2 AS backend
WORKDIR /app/api
COPY api/ ./
RUN composer install


# Final stage
FROM php:8.2-fpm-alpine AS final
WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache nginx supervisor
RUN apk add --no-cache libzip-dev zip unzip mariadb-dev linux-headers
RUN docker-php-ext-install pdo pdo_mysql zip bcmath sockets pcntl

# Copy backend files
COPY --from=backend /app/api /var/www/html

# Copy frontend files
COPY --from=frontend /app/client/dist /var/www/html/public

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html &&     chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Optimize Laravel
# Optimize Laravel (moved to run.sh)
# RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan view:cache

# Copy supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY run.sh /var/www/html/run.sh

# Expose ports
EXPOSE 80
EXPOSE 8080

# Start services using supervisor
CMD ["/bin/sh", "run.sh"]
