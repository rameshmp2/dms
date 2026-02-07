#!/bin/bash
set -e

RELEASE=$(date +%Y%m%d%H%M%S)
APP_PATH=/var/www/laravel-app

mkdir -p $APP_PATH/releases/$RELEASE

rsync -av --exclude=.git ./ $APP_PATH/releases/$RELEASE

cd $APP_PATH/releases/$RELEASE

ln -sfn $APP_PATH/shared/.env .env
ln -sfn $APP_PATH/shared/storage storage

composer install --no-dev --optimize-autoloader

php artisan migrate --force
php artisan config:clear
php artisan config:cache
php artisan route:cache

ln -sfn $APP_PATH/releases/$RELEASE $APP_PATH/current
