#!/usr/bin/env bash

cd /var/www/html

php artisan up
supervisord -c scripts/supervisord.conf
supervisorctl -c scripts/supervisord.conf reread
supervisorctl -c scripts/supervisord.conf update
supervisorctl -c scripts/supervisord.conf start all
sudo chmod 777 -R storage/