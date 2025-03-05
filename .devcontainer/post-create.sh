#! /bin/bash

cd /workspace/app/laravel &&\
    composer install &&\
    cp ../../.env . &&\
    chown -R www-data:www-data storage &&\
    php artisan key:generate &&\
    php artisan migrate --force
