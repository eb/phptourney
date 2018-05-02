FROM php:5-apache

RUN apt-get update && apt-get install -y \
        imagemagick \
	mysql-client \
    --no-install-recommends && rm -r /var/lib/apt/lists/*
RUN docker-php-ext-install mysqli

COPY docker/ /phptourney/
COPY --chown=www-data:www-data app/ /var/www/html

VOLUME /var/www/html/data

EXPOSE 80

CMD ["/bin/bash", "-c", "/phptourney/migrate.sh && exec apache2-foreground"]
