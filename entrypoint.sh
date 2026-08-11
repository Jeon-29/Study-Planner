#!/bin/sh

# Ensure all necessary storage and log folders exist
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Fix ownership and permissions so www-data can write logs and uploads
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create the public shortcut for files
php artisan storage:link

# Run database migrations on TiDB
php artisan migrate --force

# Start Apache web server
exec apache2-foreground
