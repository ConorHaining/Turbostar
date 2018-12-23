#!/usr/bin/env bash

cd /var/www/html

php artisan down
php artisan stomp:stop
sleep 15
supervisorctl -c scripts/supervisord.conf stop all