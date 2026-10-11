#!/bin/sh
set -e

# Asigna puerto por defecto si Render no lo inyecta
export PORT="${PORT:-10000}"

# Reemplaza ${PORT} en la plantilla de Nginx
envsubst '$PORT' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

# Asegura directorios de almacenamiento y permisos
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enlaza almacenamiento público de Laravel
php artisan storage:link --force || true

# Optimiza cachés de Laravel en producción si APP_KEY está definida
if [ -n "$APP_KEY" ]; then
    echo "Optimizando caches de Laravel..."
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Ejecuta migraciones automáticamente si hay conexión a base de datos
if [ -n "$DB_HOST" ]; then
    echo "Ejecutando migraciones de base de datos..."
    php artisan migrate --force || echo "Aviso: No se pudieron ejecutar migraciones automáticas. Revisa la conexión DB."
fi

echo "Iniciando Nginx y PHP-FPM con Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
