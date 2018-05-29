# Lets Discuss
Prosty system do oceniania pracowników przez menadżerów.

# Wymagania:
Serwer z php > 7.1 (możecie urzywać XAMPP-a lub kto co lubi)
https://www.apachefriends.org/pl/download.html

Jaka kolwiek baza danych MySQL może być ta co w zestawie z XAMPP-em

Composer, menadżer pakietów dla php pobrać można tu: https://getcomposer.org/download/

NodeJs + NPM - https://nodejs.org/en/ polecam lts 8

# Instalacja: 
* git clone https://github.com/SergiuszN/lets-discuss
* cd lets-discuss
* create database 
* composer install
* php app/console doctrine:schema:update --dump-sql --force
* php app/console ckeditor:install
* php app/console assets:install

# Update:
* composer install
* php app/console doctrine:schema:update --dump-sql --force

# Create super admin user: 
php app/console fos:user:create userName user@mail.com userPassword --super-admin
