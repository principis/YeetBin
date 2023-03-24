ARG PHP_EXTENSIONS="apcu imagick pdo_sqlite sqlite3"
FROM thecodingmachine/php:8.1-v4-cli-node18 as BUILD_IMAGE

WORKDIR /usr/src/app

COPY --chown=docker:docker . ./

RUN composer install --no-dev -a --ignore-platform-reqs && \
    yarn install && \
    yarn encore prod && \
    rm -rf node_modules

FROM thecodingmachine/php:8.1-v4-slim-apache

WORKDIR /var/www/html

USER root

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        sqlite3 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

USER docker

ENV APACHE_DOCUMENT_ROOT=public/

ENV APP_ENV='prod'\
    DATABASE_DSN='sqlite:/var/www/html/var/sqlite.db'

COPY --from=BUILD_IMAGE /usr/src/app ./
COPY --from=BUILD_IMAGE /usr/src/app/util/.htaccess ./public/
COPY --from=BUILD_IMAGE /usr/src/app/util/prepare_app.sh /etc/container/startup.sh

VOLUME /var/www/html/var
