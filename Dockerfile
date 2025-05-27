# Base PHP image with FPM
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libzip-dev unzip zip libonig-dev libpq-dev

# Install PHP extensions needed by Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /app

# Copy all files to container
COPY . .

# Run composer install to install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions for Laravel folders (optional, for storage and cache)
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Expose port (default Laravel port)
EXPOSE 8000

# Run migrations and start Laravel development server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
