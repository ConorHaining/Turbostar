# #!/usr/bin/env bash

# cd /var/www/html

# php artisan up
# php artisan cache:clear

# php artisan queue:work --queue=movement-0003,movement-0001,movement-0002,movement-0005,movement-0006,movement-0007,movement-0008 & > /dev/null
# php artisan queue:work --queue=schedule-create,schedule-delete & > /dev/null
# php artisan queue:work --queue=association-create,association-delete & > /dev/null
# php artisan queue:work --queue=tiploc-create,tiploc-update,tiploc-delete & > /dev/null