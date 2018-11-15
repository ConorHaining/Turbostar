#!/usr/bin/env bash

cd /var/www/html

php artisan down
php artisan stomp:stop
supervisorctl -c scripts/supervisord.conf stop all