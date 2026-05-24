FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libicu-dev libpq-dev \
    && docker-php-ext-install intl pdo pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
