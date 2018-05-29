#!/bin/bash
composer install
php app/console doctrine:schema:update --dump-sql --force