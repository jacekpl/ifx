FROM php:8.2-fpm-alpine3.18 as php
RUN docker-php-ext-install bcmath
ENV COMPOSER_HOME /.composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
