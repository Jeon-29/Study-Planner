#!/bin/sh

# Ensure SQLite file exists and permissions are correct
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Automatically run database migrations on boot
php artisan migrate --force

# Start Apache web server
exec apache2-foreground
