FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip zip libzip-dev libonig-dev libpq-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

CMD php artisan serve --host=0.0.0.0 --port=8000
