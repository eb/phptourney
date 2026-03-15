FROM php:8-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        imagemagick msmtp mariadb-client libcurl4-openssl-dev \
        python3 python3-venv && \
    apt-get clean
RUN docker-php-ext-install mysqli curl && \
    python3 -m venv /phptourney/venv && \
    /phptourney/venv/bin/pip3 install pymysql

COPY docker/config /phptourney/config
COPY docker/migration /phptourney/migration
COPY docker/manage /phptourney/manage
COPY docker/entrypoint.sh /
COPY --chown=www-data:www-data app/ /var/www/html

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    cp /phptourney/config/phptourney.ini "$PHP_INI_DIR/conf.d/" && \
    cp /phptourney/config/mail.ini "$PHP_INI_DIR/conf.d/"

VOLUME /var/www/html/data

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]