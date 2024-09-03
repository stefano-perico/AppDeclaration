FROM  interinvestcontainersregistry.azurecr.io/interinvest/php:8.3-alpine-multi

ARG SERVER_NAME=test.local

ENV SERVER_NAME ${SERVER_NAME}
#ENV FRANKENPHP_CONFIG="worker ./public/index.php"
#ENV APP_RUNTIME=Runtime\\FrankenPhpSymfony\\Runtime

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

WORKDIR /srv/app
