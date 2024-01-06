FROM php:8.0-apache

WORKDIR /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update -y

RUN apt-get upgrade -y

RUN apt install git libzip-dev zip unzip -y

RUN docker-php-ext-install zip && docker-php-ext-enable zip

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN docker-php-ext-install pdo && docker-php-ext-enable pdo

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

RUN chown -R www-data:www-data /var/www/html

RUN chmod -R 755 /var/www/html

RUN a2enmod rewrite

RUN service apache2 restart
