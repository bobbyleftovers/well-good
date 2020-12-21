#!/bin/bash
set -euo pipefail

if [ "$1" == php-fpm ]; then
    if [ "$(id -u)" = '0' ]; then
        user='www-data'
        group='www-data'
    else
        user="$(id -u)"
        group="$(id -g)"
    fi

    cp -r /usr/src/wordpress/* /var/www/html/ && chown www-data:www-data -R /var/www/html

fi

exec "$@"