===================================================================================
Res Inn - A fictional BNB running ResBooker 
===================================================================================

Since the instructions for this exercise forbid the use of an existing web framework, i felt the need to build one from scratch just for this project; a framework may be 
overkill however after going through ResRequests online presence - the design of this framework was intended to replicate a real world scenario as closely as possible therefore i designed this architecture to scale ResRequests clientbase.

The ResBooker framework follows PHP's PSR-4 standard for namespacing classes and as such only one external PHP library was utilized , i.e. composer to handle dependency 
injection as composer is now widely adopted by the PHP community at large.

===================
Setup Instructions
===================

The application is fully self contained and as such you only need three things to run this software:

1. MySQL
2. PHP 5.5 (I tested on 5.5 but PHP 5.4 and PHP 7 should work fine as well)
3. Linux - The project was tested on ubuntu and MAC OS X, but should work on most Linux or UNIX distors.


To Install:
==========

1. git clone https://github.com/kwnaidoo/ResBooker.git
2. nano / edit src/ResBooker/Config/Settings.php and add your database name, username, password 
3. cd ResBooker/
4. php bin/migrations.php 
5. sh runserver.sh
6. visit website: http://127.0.0.1:8001/

NOTE: you can pass in a port number in step 5 if 8001 is a problem for you , however you'll need to update the settings file with your port.


NOTES:
==========

These are the list of things i could do better but due to my work commitments and limited time i have had to leave them out for this task:

=> Improve security , CSRF protection on forms.
=> Captcha on forms
=> Added an authentication system for CRUD operations for the Inn's admin to manage rooms.
=> Cleaned up design elements a bit better.
=> Unit tests.
=> Improve the Migration class to be more robust.
=> Improved routes to handle more complicated routing.
=> Email reservation off to client and admin.
=> Price switching based on season. I have made provision for this in the db.
=> Credit card billing. Made provision for this paid - tinyint boolean to keep track
   of payment , and paid_amount decimal to keep track of deposit / amount paid.


ADDITIONAL RESOURCES:
====================

1. ResBooker/screenshots - contains a bunch of screen shots of the app.
2. ResBooker/notes - contains ERD diagrams.
3. Bootstrap template - Frontend based off a template from bootstrap zero.
4. Frameworks & libraries - jQuery, bootstrap , jquery datetimepicker plugin.

Framework outline:
-----------------

bin/ -- contains the migration command , this directory was designed to store all php console tasks.
migrations/ -- stores all migration files which each should return an array of SQL statements grouped by type i.e. "up" and "down"
public/ -- this is the public facing folder containing a basic framework bootstrap and dispatcher "index.php" and as well as project assets.
src/ -- Contains all the PSR-4 compliant classes and folders.
src/Config -- for settings files
src/Controllers -- to place all project controllers.
src/Models -- to place all project models.
src/Lib -- all Library classes generally stores framework core classes.
templates/ -- stores all view files , view files should be placed in sub folders named after the controller in lower case and underscored.
templates/themes -- stores application skin files to control design and layout of application.
vendor/  -- for all third party code , right now only contains the composer library code.
composer.json -- composer config file.
routes.php -- file containing an array of routes.
runserver.sh -- shell script to start the app.
