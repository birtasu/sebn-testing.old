FROM php:7.3-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli

RUN apt-get update \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip

COPY ./ ./

RUN chmod -R 775 www/resources/

COPY 000-default.conf /etc/apache2/sites-available/
