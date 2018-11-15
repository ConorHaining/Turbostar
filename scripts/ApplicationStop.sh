#!/usr/bin/env bash

cd /var/www/html

php artisan down
php artisan stomp:stop
sudo systemctl stop supervisor