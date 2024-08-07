#!/bin/sh

# Run Artisan commands
php artisan key:generate
php artisan migrate:fresh --seed

# Start php-fpm
exec php-fpm
