FROM php:7.4-fpm-alpine

RUN apk add --update --no-cache autoconf g++ make libpng-dev oniguruma-dev libxml2-dev openssl-dev \
  libjpeg-turbo-dev freetype-dev libzip-dev zlib-dev gmp-dev \
  && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
  && docker-php-ext-configure gmp \
  && docker-php-ext-install pdo pdo_mysql pcntl sockets gd bcmath gmp opcache \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer