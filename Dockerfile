#syntax=docker/dockerfile:1.4
FROM php:8.3.11-cli-alpine3.20 AS local
# base
RUN apk --no-cache add unzip tzdata librdkafka\
    $PHPIZE_DEPS linux-headers htop procps postgresql16-client bash vim\
    && rm -rf /var/cache/apk/*
ENV TZ=UTC
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN set -eux; \
    install-php-extensions zip pcntl intl bcmath amqp pdo pdo_pgsql rdkafka ds sockets \
    && rm -rf /tmp/*
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/.composer
RUN mkdir /.composer && chmod a+rwx /.composer -R
COPY --from=composer/composer:2-bin /composer /usr/bin/composer
RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY .docker/development/php/php-override.ini $PHP_INI_DIR/conf.d/php-override.ini
#RUN set -eux; pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug
ARG UID=1000
ARG USER=local
RUN adduser -D -u ${UID}  ${USER}
USER ${USER}
WORKDIR /app
