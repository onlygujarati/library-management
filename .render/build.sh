
#!/usr/bin/env bash

# Laravel build commands
composer install
php artisan config:cache
php artisan migrate --force
