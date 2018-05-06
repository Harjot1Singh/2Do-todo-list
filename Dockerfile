FROM php:7.0-apache

#RUN apt-get update && apt-get install -y \
#	php7.0-sqlite3

#COPY config/php.ini /usr/local/etc/php/
COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/

EXPOSE 80
