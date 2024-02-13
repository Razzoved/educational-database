FROM  php:8.3-apache

RUN apt-get update && apt-get install -y libicu-dev libzip-dev libonig-dev

# modules
RUN a2enmod rewrite

# php extensions
RUN docker-php-ext-install intl && docker-php-ext-enable intl
RUN docker-php-ext-install mbstring && docker-php-ext-enable mbstring
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
