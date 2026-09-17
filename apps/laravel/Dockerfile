FROM php:8.4-fpm-alpine

# Instala dependencias y extensiones PHP en una sola capa (se cachea en el build)
RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip gd intl opcache

# OPcache optimizado para producción/desarrollo
COPY docker/opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini

WORKDIR /var/www/html

CMD ["php-fpm"]
