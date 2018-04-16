FROM php:5-apache

RUN docker-php-ext-install mysqli
RUN apt-get update && apt-get install -y \
        imagemagick \
    --no-install-recommends && rm -r /var/lib/apt/lists/*

COPY --chown=www-data:www-data app/ /var/www/html

VOLUME /var/www/html/data

EXPOSE 80

