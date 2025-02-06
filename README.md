create .env and create database then run the following commands

composer install

php artisan config:cache

php artisan storage:link

php artisan migrate —seed


after migrating and seeding the database you will have an editor account with the following credentials:
login: admin@admin.com
password: admin

