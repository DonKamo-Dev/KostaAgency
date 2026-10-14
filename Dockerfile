# ── Stage 1: Build Frontend Assets (Vite) ──────────────────────
FROM node:20-alpine AS frontend-builder

WORKDIR /app

COPY apps/laravel/package*.json ./
RUN npm install

COPY apps/laravel/ ./
RUN npm run build

# ── Stage 2: Production PHP + Nginx Runtime ───────────────────
FROM php:8.4-fpm-alpine

# Instala Nginx, Supervisor, gettext (para envsubst) y librerías de soporte PHP
RUN apk add --no-cache \
        nginx \
        supervisor \
        gettext \
        ca-certificates \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip gd intl opcache bcmath

# Instala Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Configuración de OPcache para PHP
COPY apps/laravel/docker/opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini

WORKDIR /var/www/html

# Copia definiciones de dependencias PHP para aprovechar el caché de Docker
COPY apps/laravel/composer.json apps/laravel/composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader

# Copia código fuente de la aplicación
COPY apps/laravel/ ./

# Copia los assets compilados de Vite desde la etapa 1
COPY --from=frontend-builder /app/public/build ./public/build

# Optimiza autoloading de Composer
RUN composer dump-autoload --optimize --no-dev

# Configuraciones de servidor Nginx y Supervisor para Render
RUN mkdir -p /etc/nginx/conf.d /etc/nginx/http.d /run/nginx /var/log/nginx \
    && rm -rf /etc/nginx/conf.d/* /etc/nginx/http.d/* \
    && sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf 2>/dev/null || true

COPY docker/nginx-render.conf /etc/nginx/templates/default.conf.template
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint-render.sh /usr/local/bin/entrypoint-render.sh

RUN chmod +x /usr/local/bin/entrypoint-render.sh

# Permisos de carpetas de Laravel
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080 10000

ENTRYPOINT ["/usr/local/bin/entrypoint-render.sh"]
