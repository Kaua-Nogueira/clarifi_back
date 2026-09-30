#!/bin/sh
set -eu

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

if [ "${RUN_SEEDER:-false}" = "true" ]; then
    php artisan db:seed --force
fi

exec "$@"
