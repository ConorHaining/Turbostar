#!/usr/bin/env bash

cd /var/www/html

php artisan up
supervisord -c scripts/supervisord.conf
supervisorctl -c scripts/supervisord.conf