FROM php:8.4-fpm-alpine AS builder

RUN apk update && apk add \
        curl \
        autoconf \
        g++ \
        make \
        postgresql-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        imagemagick-dev \
        imagemagick

# Установка и настройка расширений для php
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install -j$(nproc) gd pdo pgsql pdo_pgsql

WORKDIR /var/www
COPY . ./

# Установка Composer под текущую версию php, затем установка зависимостей
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --optimize-autoloader --no-interaction --no-progress --prefer-dist

FROM php:8.4-fpm-alpine

RUN apk update
RUN apk add \
        curl \
        postgresql-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        imagemagick-dev \
        imagemagick

# Устанавливаем конфиг для php
ADD .deploy/php/php.prod.ini /usr/local/etc/php/php.ini

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/bin/docker-php-ext-* /usr/local/bin/

COPY --from=builder /var/www /var/www

WORKDIR /var/www

# Кешируем маршруты и виды
RUN php artisan route:cache
RUN php artisan view:cache

EXPOSE 9000
CMD ["php-fpm"]
