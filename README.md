# dashx-php

DashX SDK for PHP

## Installation

## Usage
```php
# include composer autoload
require 'vendor/autoload.php';

# import the DashX Client Class
use Dashx\Php\Client;

# create dashx instance
$dashx = new Client(
    'DASHX_PUBLIC_KEY',
    'DASHX_PRIVATE_KEY',
    'DASHX_TARGET_ENVIRONMENT',
    'DASHX_URI'
);

$dashx->deliver('email/forgot-password', [
    'data' => [
        'email' => 'dashx@example.com'
    ]
]);
```

## Integration with Laravel

To integrate DashX with Laravel, run the following artisan command to publish the configuration file:

```bash
php artisan vendor:publish --provider="Dashx\Php\Laravel\DashxServiceProvider"
```
Add DashX below env variables with values:

```bash
DASHX_URI=
DASHX_PUBLIC_KEY=
DASHX_PRIVATE_KEY=
DASHX_TARGET_ENVIROMENT=
```

## Usage with Laravel

```php
use DashX;

DashX::deliver('email/forgot-password', [
    'data' => [
      'email' => 'dashx@example.com'
    ]
]);
```
