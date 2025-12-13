FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    libpq-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    librdkafka-dev \
    cmake \
    build-essential \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1002 www-data && groupmod -g 1002 www-data

WORKDIR /var/www

COPY . /var/www

RUN sh whisper.cpp/models/download-ggml-model.sh base \
    && cmake -S whisper.cpp -B whisper.cpp/build \
    && cmake --build whisper.cpp/build -j

RUN mkdir -p /tmp/profiles && chmod -R 777 /tmp/profiles

ENTRYPOINT ["php-fpm"]
