FROM php:7-fpm-alpine

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY . /code
