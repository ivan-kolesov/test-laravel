## Get project
Clone project from GIT repository onto your local disk

`git clone git@github.com:ivan-kolesov/test-laravel.git path-to-your-project`

Change your current directory to new created: `cd path-to-your-project`

## Prepare to run
You have to install composer before run the project. See https://getcomposer.org/doc/00-intro.md for details.

Copy file `.env-example` to `.env`

Run into console `composer install`

## Run migration

Run into console `php artisan migrate`

## Seed test data

You can seed feed list with test data, run into console `php artisan db:seed` 

## Run schedule

Schedule needs to fetch posts at background, every five minutes.
Set job to cron:

`* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1`

## Run project

It's not necessary to run the project from any web server, so just run into console `php artisan serve`
and go to given url into your web browser.