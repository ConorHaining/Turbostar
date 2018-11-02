#!/usr/bin/env bash

cd /var/www/html
php artisan cache:clear
php artisan up

php artisan queue:work --queue=movement-0003,movement-0001,movement-0002,movement-0005,movement-0006,movement-0007,movement-0008 &
php artisan queue:work --queue=schedule-create,schedule-delete,association-create,association-delete,tiploc-create,tiploc-update,tiploc-delete &