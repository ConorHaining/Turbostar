#!/usr/bin/env bash

cd /var/www/html

php artisan down
sudo systemctl stop supervisor