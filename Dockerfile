ARG NGINX_VERSION=1.21
ARG PHP_VERSION=8.1.5

##################################################
#
# php-fpm stage
#
##################################################
FROM php:${PHP_VERSION}-fpm-alpine AS app_php

# Persistent / runtime deps
RUN apk add --no-cache \
    acl \
    fcgi \
    file \
    gettext \
    git \
    mysql-client

ARG APCU_VERSION=5.1.20
ARG REDIS_VERSION=5.3.4

RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        zlib-dev \
    ; \
    docker-php-ext-configure zip; \
    docker-php-ext-install -j$(nproc) \
        intl \
        pdo_mysql \
        zip \
    ; \
    pecl install \
        ast \
        apcu-${APCU_VERSION} \
        redis-${REDIS_VERSION} \
    ; \
    pecl clear-cache; \
    docker-php-ext-enable \
        ast \
        apcu \
        opcache \
        redis \
    ; \
    runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )"; \
    apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
    apk del .build-deps


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY  docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /app

# Build for production
ARG APP_ENV=prod

COPY composer.json composer.lock symfony.lock ./
RUN set -eux; \
    composer clear-cache; \
    composer install --prefer-dist --no-autoloader --no-dev --no-scripts --no-progress;

# Copy only specifically what we need
COPY bin bin/
COPY .env .env
COPY config config/
COPY migrations migrations/
COPY public public/
COPY src src/


RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer run-script --no-dev post-install-cmd; \
    chmod +x bin/console; \
    sync

VOLUME /app/var

COPY docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm"]
##################################################
#
# nginx stage
#
##################################################
FROM nginx:${NGINX_VERSION} AS server_nginx

COPY docker/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app/public

COPY --from=app_php /app/public ./

