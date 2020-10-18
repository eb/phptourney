FROM php:7-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends imagemagick msmtp mariadb-client-core-10.3 && \
    apt-get clean && \
    echo "account default\nhost mail" >> /etc/msmtprc && \
    echo 'sendmail_path = "/usr/bin/msmtp -t"' > /usr/local/etc/php/conf.d/mail.ini
RUN docker-php-ext-install mysqli

COPY config/ /phptourney/
COPY --chown=www-data:www-data app/ /var/www/html

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN cp /phptourney/phptourney.ini "$PHP_INI_DIR/conf.d/"

VOLUME /var/www/html/data

EXPOSE 80

CMD ["/bin/bash", "-c", "/phptourney/migrate.sh && exec apache2-foreground"]
