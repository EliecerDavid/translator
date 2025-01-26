FROM php:8.4-fpm

RUN apt-get update

RUN apt-get install -y \
    zip

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Get composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer
