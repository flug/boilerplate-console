FROM php:7.4-zts-alpine

WORKDIR /app

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN set -eux ; \
            apk add --no-cache --update git curl-dev autoconf libxml2-dev icu-dev   pcre-dev $PHPIZE_DEPS;\
            docker-php-ext-install curl  ;\
            docker-php-ext-install pcntl  ;\
            pecl install parallel ; \
            adduser application -u 1000  --disabled-password ;  \
            chown 1000:application -R /app ; \
            composer global require hirak/prestissimo
USER 1000
