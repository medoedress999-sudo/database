FROM php:8.1-apache


RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/


RUN chown -r www-data:www-data /var/www/html

EXPOSE 80
