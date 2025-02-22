FROM php:8.2.0-fpm-alpine3.16

# Основные зависимости
RUN docker-php-ext-configure opcache --enable-opcache && \
    apk update && apk add bash unzip git

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev \
    libzip-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql opcache zip

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

CMD ["php-fpm"]

ENV POSTGRES_USER=postgres
ENV POSTGRES_PASSWORD=secret
ENV POSTGRES_DB=dating
ENV APP_DB_USER=app
ENV APP_DB_PASSWORD=app_password
