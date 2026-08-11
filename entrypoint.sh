#!/bin/sh

# 1. Ensure all necessary storage folders exist
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views

# 2. Create the public shortcut for uploaded files
php artisan storage:link

# 3. Ensure SQLite file exists
touch /var/www/html/database/database.sqlite

# 4. Set strict permissions so the web server can upload files
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 5. Run database migrations
php artisan migrate --force

# 6. Start Apache web server
exec apache2-foreground
