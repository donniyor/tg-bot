FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    libpq-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    librdkafka-dev \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1002 www-data && groupmod -g 1002 www-data

WORKDIR /var/www

RUN mkdir -p /tmp/profiles && chmod -R 777 /tmp/profiles

ENTRYPOINT ["php-fpm"]
