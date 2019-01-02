FROM php:5-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends imagemagick ssmtp mysql-client && \
    apt-get clean && \
    echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf && \
    echo 'sendmail_path = "/usr/sbin/ssmtp -t"' > /usr/local/etc/php/conf.d/mail.ini
RUN docker-php-ext-install mysqli

COPY docker/ /phptourney/
COPY --chown=www-data:www-data app/ /var/www/html

VOLUME /var/www/html/data

EXPOSE 80

CMD ["/bin/bash", "-c", "/phptourney/migrate.sh && exec apache2-foreground"]
