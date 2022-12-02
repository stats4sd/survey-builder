# Survey Builder
This platform is built to support the creation of ODK forms from a library of pre-built ODK modules. The platform includes an admin panel for users to manage the list of ODK modules available, and a front-end written in VueJS that lets users create new ODK forms. 

This system was built in collaboration with RHOMIS, and the RHoMIS-specific version of this system now lives in their organisation's github page: https://github.com/RHoMIS. 

This version is currently being updated to be more generalised, and hopefully will be useable in different scenarios. 

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
6.	Migrate the database: `php aritsan migrate:fresh --seed` (or copy from the staging site, if one exists.)


