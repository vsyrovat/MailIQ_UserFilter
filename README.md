# Flexible Elements Filtration using Doctrine and Symfony 3.4
The Test Job

## Requirements
* PHP 7.2.x (tested with 7.2.4)
* MySQL 5.x (tested with 5.7)

## Installation
* Clone this project
* Run ```composer install```
* Set up MySQL connection and database name in ```app/config/parameters.yml```
* Run ```bin/console doctrine:database:create```
* Run ```bin/console doctrine:migrations:migrate```
* Run ```bin/console doctrine:fixtures:load```

## Show the Demo
* Run ```bin/console server:start```
* Open given link (supposable http://127.0.0.1:8000)

## Service Usage
Filter Constructor placed in src/AppBundle/Service/FilterConstructor.php

Tests placed in tests/AppBundle/Service/FilterConstructorTest.php

Usage examples placed in AppBundle:DefaultController#filterAction
