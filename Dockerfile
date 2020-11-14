FROM php:7-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends imagemagick msmtp mariadb-client-core-10.3 && \
    apt-get clean
RUN docker-php-ext-install mysqli

COPY docker/config /phptourney/config
COPY docker/migration /phptourney/migration
COPY docker/entrypoint.sh /
COPY --chown=www-data:www-data app/ /var/www/html

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN cp /phptourney/config/phptourney.ini "$PHP_INI_DIR/conf.d/"
RUN cp /phptourney/config/mail.ini "$PHP_INI_DIR/conf.d/"

VOLUME /var/www/html/data

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]