#!/usr/bin/env bash

cd /var/www/html

php artisan up
sudo systemctl start supervisor