
# The API Webshop

Simplified mini webshop. It consists of customers, products and orders. Written in PHP (Laravel) and setup using docker with sail.


## Installation

#### Clone Project
```bash
  git clone https://github.com/ruddesh/loop-backend-api.git loop-backend-api
```
#### change directory
```bash
  cd loop-backend-api
```
#### sail
```bash
  composer require laravel/sail --dev
```
#### .env file
```bash
  cp .env.example .env
```
#### generate key
```bash
  php artisan key:generate
```

#### sail composer
```bash
  php artisan sail:install
```

#### run sail up to create container for loop-api-backend with mysql
```bash
  ./vendor/bin/sail up
```
#### open laravel running container in CLI (through docker desktop), run artisan commands to run migrations and import csv data into customers and products
```bash
  php artisan migrate
  php artisan import:csv-data 
```
## Endpoints
```bash
    GET api/orders              -- get orders
    POST api/orders             -- create order
    PUT api/orders/{order}      -- update order
    DELETE api/orders/{order}   -- delete order
    POST api/orders/{order}/add -- add product to order
    POST api/orders/{order}/pay -- pay order
```
## Postman API Doc

https://documenter.getpostman.com/view/15243104/2s9Y5YR2md
## Tech Stack

**Server:** PHP, Laravel, Mysql, Docker
