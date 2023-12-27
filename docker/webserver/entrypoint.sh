#!/usr/bin/env bash

if [ ! -z "$APP_USER_ID" ]; then
    if [ $APP_USER_ID -gt 0 ]; then
        usermod -u $APP_USER_ID app
    fi
fi

if [ ! -z "$APP_GROUP_ID" ]; then
    groupmod -g $APP_GROUP_ID app
    usermod -g $APP_GROUP_ID app
fi

if [ ! -d /.composer ]; then
    mkdir /.composer
fi

chmod -R ugo+rw /.composer

if [ $# -gt 0 ]; then
    if [ $APP_USER_ID -gt 0 ];
        then exec gosu app "$@"
        else exec "$@"
    fi
else
    gosu www-data php artisan migrate --force --no-interaction
    gosu www-data php artisan db:seed --force --no-interaction --class=ProductSeeder
    gosu www-data php artisan scout:sync-index-settings
    exec supervisord -n -c /etc/supervisor/supervisord.conf
fi
