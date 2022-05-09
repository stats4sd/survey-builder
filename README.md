# Stats4SD Laravel Platform Template
This installation is a 'default' Laravel 8.X installation for Stats4SD projects with a host of configuration and core packages already setup.

It includes the following composer packages:

- Laravel Backpack (backpack/crud)
- Backpack's version of the Spatie Permission Manager (backpack/ b)
- Laravel Telescope

And the following dev packages for scaffolding and code helpers:

- [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper)
- stats4sd/laravel-ui


## Laravel Backpack for Admin panel
This template comes with Laravel Backpack to help quickly build CRUD panels for data management. We have made the following changes to the default configuration:

 - Backpack uses the 'web' guard for authentication, so users do not need to log into the 'admin panel' seperately from the 'front-end'.
 - Backpack's auth routes are *not* enabled. Instead, use the auth routes setup through Laravel Breeze (located in `routes/auth.php`).

In this default setup, **any authenticated user can access the admin panel**. We often stick with this setup as our platforms are mostly or entirely within the admin panel. Given this, please make good use of CRUD::denyAccess() within the platform's Crud Controllers.

TODO: setup push to push locations csv file to Kobo
TODO: update XLS form module(s) to use locations csv instead of choice_filters



TODO: modify household question to be either select or string;
TODO: add customise question text section;

TODO: update optional modules with correct language column headers
TODO: add rules to modules for ordering
TODO: for non-finalised forms, link to modules instead of module versions, so the user always has access to the 'current' versions of each module? 
