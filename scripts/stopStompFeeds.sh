#!/usr/bin/env bash

cd /var/www/html
php artisan stomp:stop
pwd > /home/ubuntu/loc.txt