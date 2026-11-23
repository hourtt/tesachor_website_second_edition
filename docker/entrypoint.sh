#!/bin/sh
set -e

# Port configuration (supports Railway, Render, Fly.io, Cloud Run $PORT)
PORT="${PORT:-80}"
echo "==> Configuring Nginx to bind to 0.0.0.0:${PORT}..."

if [ -f /etc/nginx/http.d/default.conf ]; then
    sed -i "s/listen .*/listen 0.0.0.0:${PORT} default_server;/g" /etc/nginx/http.d/default.conf
elif [ -f /etc/nginx/conf.d/default.conf ]; then
    sed -i "s/listen .*/listen 0.0.0.0:${PORT} default_server;/g" /etc/nginx/conf.d/default.conf
fi

nginx -t || echo "Warning: Nginx configuration test returned a warning."

# Ensure Nginx temporary and run directories exist with proper www-data ownership
mkdir -p /var/lib/nginx/tmp/client_body \
         /var/lib/nginx/tmp/proxy \
         /var/lib/nginx/tmp/fastcgi \
         /run/nginx \
         /var/log/nginx
chown -R www-data:www-data /var/lib/nginx /run/nginx /var/log/nginx
chmod -R 755 /var/lib/nginx

# Ensure storage subdirectories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink if missing
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link || true
fi

# Check for APP_KEY
if [ -z "${APP_KEY}" ]; then
    echo "==> WARNING: APP_KEY is not set in Railway environment variables! Sessions and encryption will fail."
fi

# Run database migrations if enabled via environment variable
if [ "${AUTORUN_MIGRATIONS:-false}" = "true" ] || [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || echo "==> WARNING: Database migration failed. Check your database credentials in Railway variables."
fi

# Cache optimization for production
if [ "${APP_ENV:-production}" = "production" ] && [ -n "${APP_KEY}" ]; then
    echo "Caching Laravel configuration, routes, and views..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

echo "Starting services via Supervisor..."
exec "$@"
