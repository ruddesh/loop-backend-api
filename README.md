
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
#### open laravel running container in CLI (through docker desktop) and run artisan command to import csv data into customers and products
```bash
  php artisan import:csv-data 
```
## Endpoints
```bash
    GET api/orders              -- get orders
    POTS api/orders             -- create order
    PUT api/orders/{order}      -- update order
    DELETE api/orders/{order}   -- delete order
    POST api/orders/{order}/add -- add product to order
    POTS api/orders/{order}/pay -- pay order
```

## Tech Stack

**Server:** PHP, Laravel, Mysql, Docker
