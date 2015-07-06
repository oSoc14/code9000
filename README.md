code9000
========

EduCal is an application for schools to easily create and manage calendars that can be shared with parents.

##Developed with
* Laravel 4
* Cartalyst Sentry
* jQuery
* Twitter Bootstrap
* FullCalendar.js
* DataTables.js

##How to install
Make sure you have composer installed
> [Install composer](https://getcomposer.org/doc/00-intro.md)

Begin by installing the required packages through Composer.

    composer install
    
Next change your database credentials

* Go to folder app/config
* Copy the file database.php to app/config/local
* Update the new database.php file with your own database credentials
* [database credentials](http://laravel.com/docs/database)
    
Finally run the migrations to create your database.

    php artisan migrate
    
If your development environment is not on a '.dev' domain, you will have to change the following line of code in bootstrap/start.php:

> $env = $app->detectEnvironment(array(
	'local' => array('*.dev'),
));

And change '\*.dev' to your computer name or simply '\*'



## What is this?
Schools can have a lot of activities throughout the year such as commitee meetings, open days for the public, kid's parties, etc. This can be hard to keep track of for parents. EduCal centralizes and creates an easy-to-use link to import the school's agenda.

This project has been programmed during the open Summer of code 2014 and worked further upon in 2015.

## Credit
Author: Sander Meert

Past Authors: Bjorn Van Acker, Nick Denys

Special thanks to the City of Ghent, Digipolis, Jan Vansteelandt and Pieter Colpaert.
