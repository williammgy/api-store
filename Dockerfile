FROM php:8.1-apache

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/
COPY ./config/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80