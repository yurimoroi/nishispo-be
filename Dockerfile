FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libmagickwand-dev \
    imagemagick \
    --no-install-recommends && \
    pecl install imagick && \
    docker-php-ext-enable imagick

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip gd

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www

RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
