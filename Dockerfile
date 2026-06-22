<<<<<<< HEAD
FROM php:8.1-apache


RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/


RUN chown -r www-data:www-data /var/www/html

EXPOSE 80
=======
FROM php:8.1-apache


RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/


RUN chown -r www-data:www-data /var/www/html

EXPOSE 80
>>>>>>> 4ebc7917d22e4d216ce7ecc7b2212b0930b16cbf
