
**Res Inn - A BNB CMS framework**

ResBooker is a bnb booking framework built from scratch that follows PHP's PSR-4 standard for namespacing classes and as such only one external PHP library was utilized , i.e. composer to handle dependency 
injection as composer is now widely adopted by the PHP community at large.

**Setup Instructions**

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

These are the list of things I could do better but since this is just a prototype concept project and I have limited time - they have not been implemented:

1. Improve security , CSRF protection on forms.
2. Captcha on forms
3. Added an authentication system for CRUD operations for the Inn's admin to manage rooms.
4. Cleaned up design elements a bit better.
5. Unit tests.
6. Improve the Migration class to be more robust.
7. Improved routes to handle more complicated routing.
8. Email reservation off to client and admin.
9. Price switching based on season. I have made provision for this in the db.
10. Credit card billing. Made provision for this paid - tinyint boolean to keep track
   of payment , and paid_amount decimal to keep track of deposit / amount paid.


ADDITIONAL RESOURCES:
====================

1. ResBooker/screenshots - contains a bunch of screen shots of the app.
2. ResBooker/notes - contains ERD diagrams.
3. Bootstrap template - Frontend based off a template from bootstrap zero.
4. Frameworks & libraries - jQuery, bootstrap , jquery datetimepicker plugin.

Framework outline:
-----------------

1. bin/ -- contains the migration command , this directory was designed to store all php console tasks.
2. migrations/ -- stores all migration files which each should return an array of SQL statements grouped by type i.e. "up" and "down"
3. public/ -- this is the public facing folder containing a basic framework bootstrap and dispatcher "index.php" and as well as project assets.
4. src/ -- Contains all the PSR-4 compliant classes and folders.
5. src/Config -- for settings files
6. src/Controllers -- to place all project controllers.
7. src/Models -- to place all project models.
8. src/Lib -- all Library classes generally stores framework core classes.
9. templates/ -- stores all view files , view files should be placed in sub folders named after the controller in lower case and underscored.
10. templates/themes -- stores application skin files to control design and layout of application.
11. vendor/  -- for all third party code , right now only contains the composer library code.
12. composer.json -- composer config file.
13. routes.php -- file containing an array of routes.
14. runserver.sh -- shell script to start the app.
