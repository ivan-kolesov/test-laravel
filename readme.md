## About

RSS reader allows read your favourites RSS feeds in web browser.

You can add feeds, edit and remove them.

Post that had been read mark as read, so you don't need to read it twice. 

## Get project
Clone project from git repository to your local disk

`git clone git@github.com:ivan-kolesov/test-laravel.git path-to-your-project`

Change your current directory to new created: `cd path-to-your-project`

## Installation

### 1. Fast installation

You have to install composer before run the project. See https://getcomposer.org for details.
If you'll chose this, skip steps 2-4.

Just run in your console bash script `init.sh` which creates local sqlite database and populates the database with test data.

### 2. Prepare to run

Copy file `.env-example` to `.env`

Run in console `composer install`

### 3. Run migration

Run in console `php artisan migrate`

### 4. Seed test data

You can seed feed list with test data, run in console `php artisan db:seed` 

## Run schedule

Schedule needs to fetch posts at background, every minute.
Set job to cron:

`* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1`

You can skip this step and run schedule manually, just run in console `php artisan feeds:fetch` every time when you want to fetch new remote data.

## Run project

It's not necessary to run the project from any web server, so just run in console `php artisan serve`
and go to given url in your web browser.

## Run unit tests

Unit tests flush the database during it running, so make backup before run unit test.

Run `phpunit` in the projects's root category. PhpUnit has to be installed, see more on https://phpunit.de

## Customization

You can change RSS parser to your own realization. Implement `App\Adapters\AdapterInterface` at new adapter class and
point the classes name at config `app.php`, like `'feedAdapter' => App\Adapters\YourAdapter::class`.