#!/usr/bin/env bash

cd /var/www/html

php artisan up
supervisord -c scripts/supervisord.conf
sudo systemctl start supervisor