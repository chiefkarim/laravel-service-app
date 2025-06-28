
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
COPY api/composer.json api/composer.lock ./
RUN composer install --no-dev --no-scripts
COPY api/ ./
RUN composer dump-autoload --no-dev --optimize

# Final stage
FROM php:8.2-fpm-alpine AS final
WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache nginx

# Copy backend files
COPY --from=backend /app/api /var/www/html

# Copy frontend files
COPY --from=frontend /app/client/dist /var/www/html/public

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start services
CMD ["/bin/sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
