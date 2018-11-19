# Hotelify-php
Hotelify is a hotel booking platform that provides not only customers the best booking experiences but also hotel managers the ability to see their business grows.

Hotelify backend is written in PHP with Slim3.

### How to Run it
* Make sure you have installed [php](http://php.net/downloads.php), [phpMyAdmin](https://www.phpmyadmin.net/) (You could use [XAMPP](https://www.apachefriends.org/index.html) which integrates all the cool stuffs for you!)
* We use [composer](https://getcomposer.org/) as the dependency manager for the back-end
* Go to localhost/phpmyadmin to see if environment is set up, and you can also see the local database
* Create a hotelify databsae in phpMyAdmin `CREATE DATABASE hotelify;`
* Make sure you have a database user `root`(no password) that have the privileges for accessing/modifying the database (or you can use your customize user by modifying the [db.php](src/config/db.php))
* You can dump the database with [hotelify.sql](_sql/hotelify.sql)
* Install all the dependencies by `composer install`
* Make sure the php server is up, and you are good to go!




