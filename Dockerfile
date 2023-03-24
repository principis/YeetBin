ARG PHP_EXTENSIONS="apcu imagick pdo_sqlite sqlite3"
FROM thecodingmachine/php:8.1-v4-cli-node18 as BUILD_IMAGE

WORKDIR /usr/src/app

USER root
COPY . ./

RUN composer install --no-dev -a --ignore-platform-reqs && \
    yarn install && \
    yarn encore prod && \
    rm -rf node_modules

FROM thecodingmachine/php:8.1-v4-slim-apache

USER root
WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        sqlite3 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ENV APACHE_DOCUMENT_ROOT=/app/public/

ENV APP_ENV='prod'\
    DATABASE_DSN='sqlite:/var/www/html/var/sqlite.db'

COPY --from=BUILD_IMAGE /usr/src/app ./
COPY --from=BUILD_IMAGE /usr/src/app/util/.htaccess ./public/
COPY --from=BUILD_IMAGE /usr/src/app/util/prepare_app.sh /etc/container/startup.sh

VOLUME /app/var
