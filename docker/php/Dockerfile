FROM php:7.4-apache

RUN pecl install redis-5.1.1 \
    && docker-php-ext-enable redis
