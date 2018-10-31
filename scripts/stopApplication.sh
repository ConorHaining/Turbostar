#!/usr/bin/env bash

cd /var/www/html
php artisan down
rm -rf /storage/app/schedule