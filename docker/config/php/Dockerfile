FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
curl \
wget \
libzip-dev \
libpng-dev \
libonig-dev

RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./php.ini /usr/local/etc/php/php.ini

WORKDIR /data

CMD ["php-fpm"]
