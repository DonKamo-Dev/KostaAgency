# ── Production PHP 8.4 + Nginx Runtime (Alpine 3.21 con Binarios Precompilados) ──
FROM alpine:3.21

# Instala Nginx, Supervisor, PHP 8.4 y todas sus extensiones directamente como BINARIOS PRECOMPILADOS
# (Cero compilación de C/C++, cero sobrecarga de memoria, instalación en menos de 10 segundos)
RUN apk add --no-cache \
        nginx \
        supervisor \
        gettext \
        ca-certificates \
        curl \
        php84 \
        php84-fpm \
        php84-pdo_mysql \
        php84-sqlite3 \
        php84-pdo_sqlite \
        php84-pdo_pgsql \
        php84-pgsql \
        php84-zip \
        php84-gd \
        php84-intl \
        php84-opcache \
        php84-bcmath \
        php84-session \
        php84-tokenizer \
        php84-xml \
        php84-xmlreader \
        php84-xmlwriter \
        php84-dom \
        php84-curl \
        php84-mbstring \
        php84-fileinfo \
        php84-openssl \
        php84-phar \
        php84-ctype \
        php84-iconv \
    && ln -sf /usr/bin/php84 /usr/bin/php \
    && ln -sf /usr/bin/php84 /usr/local/bin/php \
    && ln -sf /usr/sbin/php-fpm84 /usr/sbin/php-fpm \
    && ln -sf /usr/sbin/php-fpm84 /usr/local/bin/php-fpm \
    && ln -sf /usr/sbin/php-fpm84 /usr/bin/php-fpm

# Crea y configura el usuario www-data estándar para Alpine
RUN addgroup -g 82 -S www-data 2>/dev/null || true \
    && adduser -u 82 -D -S -G www-data www-data 2>/dev/null || true

# Configura PHP-FPM en puerto 9000 con el usuario www-data
RUN if [ -f /etc/php84/php-fpm.d/www.conf ]; then \
        sed -i 's/^listen = .*/listen = 127.0.0.1:9000/g' /etc/php84/php-fpm.d/www.conf && \
        sed -i 's/^user = .*/user = www-data/g' /etc/php84/php-fpm.d/www.conf && \
        sed -i 's/^group = .*/group = www-data/g' /etc/php84/php-fpm.d/www.conf; \
    fi

# Instala Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Configuración de OPcache para PHP 8.4
COPY apps/laravel/docker/opcache.ini /etc/php84/conf.d/zz-opcache.ini

WORKDIR /var/www/html

# Copia definiciones de dependencias PHP para aprovechar el caché de Docker
COPY apps/laravel/composer.json apps/laravel/composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader

# Copia código fuente de la aplicación (incluye los assets ya compilados de public/build)
COPY apps/laravel/ ./

# Optimiza autoloading de Composer
RUN composer dump-autoload --optimize --no-dev

# Configuraciones de servidor Nginx y Supervisor para producción (Hyperlift / Render)
RUN mkdir -p /etc/nginx/conf.d /etc/nginx/http.d /run/nginx /var/log/nginx /var/log/php84 \
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
