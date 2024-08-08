#!/bin/sh

# Wait for MySQL to be ready
until nc -z db 3306; do
  echo "Waiting for the database..."
  sleep 2
done

# Run Artisan commands
php artisan key:generate
php artisan migrate:fresh --seed

# Start php-fpm
exec php-fpm
