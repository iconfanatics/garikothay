#!/bin/bash
set -Eeuo pipefail

DEPLOY_PATH="${DEPLOY_PATH:-/home/momin879/e-commerce}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-master}"
RUN_NPM_BUILD="${RUN_NPM_BUILD:-false}"

cd "$DEPLOY_PATH" || exit

git pull --ff-only origin "$DEPLOY_BRANCH"

composer install --no-interaction --prefer-dist --optimize-autoloader

if [ "$RUN_NPM_BUILD" = "true" ]; then
    npm install
    npm run build
fi

php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
