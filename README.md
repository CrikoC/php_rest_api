# php_rest_api
A PHP Rest API with authentication using JWT.

You can register a user with given "username" and "password".
When you log in successfully, a token with be generated to authenticate the user,
so that he can view, edit his profile and manage posts.

## Requirements
1. PHP - Apache - MySQL server (XAMPP, WAMP, MAMP)
2. Composer

***

## Contents

### All constants and classes are in the "includes" folder.

1. initialize.php stores all the used constants and includes all the necessary files.

2. database.php is used for connection to the database (You can edit this to your liking)

3. The database_object class manages CRUD actions for all created tables

4. users and posts are children of the database_object class. (you can add yours, according to your database)

5. the Rest class manages data validation and token authentication

6. The Api class is a child of Rest and manages all the requests (You can edit to your liking)

## Setup

1. Create a database and import "php_api.sql".
2. Edit your database info in "database.php" from the "includes" folder.