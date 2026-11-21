# Stage 1: Build Frontend Assets (Vite)
FROM node:22-alpine AS node-builder
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build

# Stage 2: Install Composer Dependencies
FROM composer:2 AS composer-builder
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

# Stage 3: Production PHP-FPM + Nginx Image
FROM php:8.3-fpm-alpine

LABEL maintainer="Tesachor Personal Developer"
LABEL description="Production container for Tesachor Laravel 12 Application"

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    postgresql-dev \
    oniguruma-dev \
    bash

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        intl \
        opcache \
        exif \
        bcmath \
        pcntl

# Install Composer binary for runtime artisan commands
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure Nginx & Supervisor directories
RUN mkdir -p /run/nginx /var/log/supervisor /etc/nginx/http.d

# Copy custom configurations
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Set working directory
WORKDIR /var/www/html

# Copy application source code
COPY . .

# Copy production vendor dependencies from Stage 2
COPY --from=composer-builder /app/vendor ./vendor

# Copy compiled Vite assets from Stage 1
COPY --from=node-builder /app/public/build ./public/build

# Finish composer autoloader optimization
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Ensure proper ownership and permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Healthcheck using Laravel 12 built-in health endpoint
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD curl -f http://127.0.0.1:${PORT:-80}/up || exit 1

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
