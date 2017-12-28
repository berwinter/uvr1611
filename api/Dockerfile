FROM php:7-fpm-alpine

RUN apk add --update --no-cache curl-dev

RUN docker-php-ext-install mysqli sockets curl

COPY . /code
