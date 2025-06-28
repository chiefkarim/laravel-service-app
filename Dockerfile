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
RUN touch database/database.sqlite &&     chmod 664 database/database.sqlite


# Final stage
FROM php:8.2-fpm-alpine AS final
WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache nginx

# Copy backend files
COPY --from=backend /app/api /var/www/html

# Copy frontend files
COPY --from=frontend /app/client/dist /var/www/html/public

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html &&     chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Optimize Laravel

# Expose port 80
EXPOSE 80

# Start services
COPY run.sh /usr/local/bin/run.sh
RUN chmod +x /usr/local/bin/run.sh
CMD ["run.sh"]