FROM php:7.3-alpine

WORKDIR /app

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache --update git  && \
            adduser application -u 1000  --disabled-password  && \
            chown 1000:application -R /app && \
            composer global require hirak/prestissimo

USER 1000
