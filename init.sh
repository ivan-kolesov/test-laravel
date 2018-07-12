#!/bin/bash

cp .env-example .env
touch database/database.sqlite
composer install
php artisan migrate:fresh --seed
php artisan feeds:fetch

yarn install
yarn run prod