# TODOLIST - Task management application

Project number 8 of OpenClassrooms "Développeur d'application PHP / Symfony" cursus

The objective of this project is to improve an exciting project and produce technical documentation.

## Project base

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Installation

### 1 - Download or clone the repository git

```console
git clone https://github.com/sebastien-chomy/oc_todolist.git my_project
```

### 2 - Download dependencies

From **my_project**
```console
composer install
```
Before you start using Composer, you must first install it on your system. https://getcomposer.org/

## Create database, schema and load data fixtures 
Follow these steps
 
### 1 - Create database
From **/my_project/**
```
php bin/console doctrine:database:create
```

### 2 - Create schema
From **/my_project/**
```console
php bin/console doctrine:schema:create
```
OR
```console
php bin/console doctrine:schema:update --force
```

### 3 - Fixtures of data
From **/my_project/**
```console
php bin/console doctrine:fixture:load
```

### 4 - Preparation
From **/my_project/**
```console
php bin/console cache:clear --env=prod 
```

### 5 - Run
From **/my_project/**
```
PHP -S localhost:8080
```
and from your browser
Production version
```
http://localhost:8080/web/app.php
```
OR
Development version 
```
http://localhost:8080/web/app_dev.php
```

## Users
To access the application's various features

User     | login    | Password
-------- | -------- | --------
generic user  | user | 12345
10 special users | username_[1..9] | 12345
Administrator | admin | 12345
