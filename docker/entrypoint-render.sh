#!/bin/sh
set -e

# Asigna puerto por defecto: 8080 para Spaceship Hyperlift, o el inyectado por la plataforma ($PORT)
export PORT="${PORT:-8080}"

# Asegura directorios de Nginx en Alpine y limpia conf.d
mkdir -p /etc/nginx/http.d /run/nginx /var/log/nginx
rm -rf /etc/nginx/conf.d/* /etc/nginx/http.d/*

# Configura el puerto dinámicamente en Nginx
sed "s/10000/${PORT}/g" /etc/nginx/templates/default.conf.template > /etc/nginx/http.d/default.conf


# Ajusta usuario de Nginx a www-data para evitar problemas de permisos
if [ -f /etc/nginx/nginx.conf ]; then
    sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf
fi

# Asegura límites de subida para PHP-FPM
mkdir -p /etc/php84/conf.d /usr/local/etc/php/conf.d
cat << 'EOF' > /etc/php84/conf.d/zz-uploads.ini
upload_max_filesize = 25M
post_max_size = 30M
memory_limit = 256M
EOF
cp /etc/php84/conf.d/zz-uploads.ini /usr/local/etc/php/conf.d/zz-uploads.ini 2>/dev/null || true

# Valida sintaxis de Nginx antes de arrancar
nginx -t

# Asegura directorios de almacenamiento y permisos de Laravel
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enlaza almacenamiento público de Laravel si no existe
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link --force || true
fi

# Ejecuta migraciones automáticamente en producción
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force || echo "Aviso: No se pudieron ejecutar migraciones automáticas. Revisa la conexión DB."

# Optimiza cachés de Laravel en producción si APP_KEY está definida
if [ -n "$APP_KEY" ]; then
    echo "Optimizando caches de Laravel..."
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "Iniciando Nginx y PHP-FPM con Supervisor en el puerto ${PORT}..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
