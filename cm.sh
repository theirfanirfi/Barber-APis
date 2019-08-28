#!/bin/bash

#clear
if [ $1 == "b" ];then
    php artisan make:controller $2"Controller"
    php artisan make:model Models/$2
elif [ $1 == "c" ];then
    php artisan make:controller $2"Controller"
elif [ $1 == "m" ];then
    php artisan make:model $1
fi
echo "Argument not supplied"


php artisan cache:clear
php artisan config:clear
php artisan route:clear
