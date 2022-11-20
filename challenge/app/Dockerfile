FROM php:7.2-apache

COPY ./src/ /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
RUN mkdir /var/data /var/security && chmod -R 777 /var/data /var/security && chmod o-w /var/www/html/ && chmod u-w /var/www/html/ && chown -R www-data:www-data /var/www/html/

USER www-data
RUN cd /var/www/html && chmod -R u=rx,go=rx *
USER root
COPY ./dijazjdioazjidoazjiodjiazjiodjazio.txt /