#! /bin/bash

cd /workspace/app/laravel &&\
    composer install &&\
    cp ../../.env . &&\
    php artisan key:generate &&\
    php artisan migrate
