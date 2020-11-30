FROM php:7.4-fpm

RUN apt-get -y update
RUN curl -L -C - --progress-bar -o /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod 755 /usr/local/bin/composer
RUN apt-get install -y git mc zlib1g-dev libpng-dev libjpeg-dev
RUN docker-php-ext-install pdo_mysql mysqli gd
RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install xdebug-2.9.6 && docker-php-ext-enable xdebug
RUN echo "date.timezone=UTC" >> /usr/local/etc/php/conf.d/timezone.ini
