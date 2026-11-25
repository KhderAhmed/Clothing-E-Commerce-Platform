# المرحلة 1: Composer
FROM composer:2.7 AS composer-build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --no-interaction --no-plugins --no-scripts \
    --prefer-dist --optimize-autoloader --ignore-platform-reqs

# المرحلة 2: Vite
FROM node:20-alpine AS node-build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# المرحلة 3: الإنتاج
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx supervisor

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd pdo_pgsql pgsql mbstring exif pcntl bcmath zip opcache \
    && rm -rf /tmp/*

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# --chown بدل chown -R حتى ما تتضاعف الطبقة
COPY --chown=www-data:www-data . .
COPY --from=composer-build --chown=www-data:www-data /app/vendor ./vendor
COPY --from=node-build --chown=www-data:www-data /app/public/build ./public/build

RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80 8080

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]