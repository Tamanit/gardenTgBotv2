FROM php:8.4

RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y zlib1g-dev \
    libpq-dev \
    libzip-dev \
    unzip

RUN docker-php-ext-install pdo pdo_pgsql sockets zip

RUN mkdir /app

ADD . /app

WORKDIR /app

RUN composer install

CMD php artisan serve --host=127.0.0.1 --port=80

EXPOSE 80
