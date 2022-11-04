# Survey Builder
A refreshed ODK form survey builder, built specifically for RHoMiS 2.0.

# Development
This platform is built using Laravel/PHP. The front-end is written in VueJS and the admin panel uses Backpack for Laravel.

## Setup Local Environment
1.	Clone repo: `git@github.com:stats4sd/survey-builder.git`
2.	Copy `.env.example` as a new file and call it `.env`
3.	Update variables in `.env` file to match your local environment:
    1. Check APP_URL is correct
    2.	Update DB_DATABASE (name of the local MySQL database to use), DB_USERNAME (local MySQL username) and DB_PASSWORD (local MySQL password)
    3.	If you need to test the ODK Central link, make sure QUEUE_CONNECTION is set to `database` or `redis` (and that you have redis setup locally). Also add your test ODK_USERNAME and ODK_password
4.	Create a local MySQL database with the same name used in the `.env` file
5.	Run the following setup commands in the root project folder:
```
composer install
php artisan key:generate
php artisan backpack:install
npm install
npm run dev
```
6.	Migrate the database: `php aritsan migrate:fresh --seed` (or copy from the staging site)




TODO: setup push to push locations csv file to Kobo

TODO: update XLS form module(s) to use locations csv instead of choice_filters

TODO: modify household question to be either select or string;

TODO: add customise question text section;

TODO: add rules to modules for ordering

TODO: for non-finalised forms, link to modules instead of module versions, so the user always has access to the 'current' versions of each module? 

TODO: provide user feedback for 419 errors (please re-login)
