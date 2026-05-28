#!/bin/bash
cd /home/momin879/e-commerce || exit

git pull origin main

composer install --no-interaction --prefer-dist --optimize-autoloader

npm install
npm run build

php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
