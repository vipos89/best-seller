

## Lendflow

## Table of contents.

- [Requirements.](#system-requirements)
- [Installation instructions.](#installation-instructions)

## System requirements
- PHP >=8.2
- Laravel ^12.0
- Redis
- Mysql/Mariadb (>=16)

## Installation instructions.
- Install containers via docker ```docker compose up -d```
- Install dependencies via composer ```docker exec laravel_app composer install```
- Copy/Create .env file ```docker exec laravel_app php -r "file_exists('.env') || copy('.env.example', '.env');```
- Generate app key ```docker exec laravel_app php artisan key:generate```
- Add NYT Book api key to .env file ```dotenv
  NYT_API_KEY=
```
