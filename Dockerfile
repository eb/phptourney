FROM php:7-apache-stretch

RUN echo "deb http://archive.debian.org/debian stretch main" > /etc/apt/sources.list
RUN rm -rf /etc/apt/sources.list.d
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        imagemagick msmtp mariadb-client \
        python3 python3-venv && \
    apt-get clean
RUN docker-php-ext-install mysqli
RUN python3 -m venv /phptourney/venv
RUN /phptourney/venv/bin/pip3 install pymysql

COPY docker/config /phptourney/config
COPY docker/migration /phptourney/migration
COPY docker/manage /phptourney/manage
COPY docker/entrypoint.sh /
COPY --chown=www-data:www-data app/ /var/www/html

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN cp /phptourney/config/phptourney.ini "$PHP_INI_DIR/conf.d/"
RUN cp /phptourney/config/mail.ini "$PHP_INI_DIR/conf.d/"

VOLUME /var/www/html/data

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]