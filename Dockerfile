FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql zip intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

COPY . .

RUN composer dump-autoload --optimize && \
    php bin/console cache:clear --env=prod --no-warmup || true

ENV APP_ENV=prod
ENV SYMFONY_ENV=prod

CMD ["php-fpm"]
