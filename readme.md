# Tire Management Application

## Getting Started
Do the following command below to start this project

```
git clone //repo
composer install
php artisan key:generate
```

## Required plugin
Some required plugin to start the project with Lumen

### Lumen Generator
This package is for additional CLI command. Ex: `php artisan key:generate`, `php artisan tinker`. All completes command [https://github.com/flipboxstudio/lumen-generator](https://github.com/flipboxstudio/lumen-generator)

#### Install additional package
`composer require flipbox/lumen-generator`

#### Configuration
Just copy paste code below to your `bootstrap/app.php`

`$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);`
