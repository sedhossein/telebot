FROM php:7.4-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html/

WORKDIR /var/www/html/

#CMD [ "php", "./index.php" ]