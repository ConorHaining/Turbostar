#!/usr/bin/env bash

cd /var/www/html

php artisan cache:clear
rm -rf storage/app/schedule/*
rm -rf storage/framework/*