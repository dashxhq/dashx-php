<p align="center">
    <br />
    <a href="https://dashx.com"><img src="https://raw.githubusercontent.com/dashxhq/brand-book/master/assets/logo-black-text-color-icon@2x.png" alt="DashX" height="40" /></a>
    <br />
    <br />
    <strong>Your All-in-One Product Stack</strong>
</p>

<div align="center">
  <h4>
    <a href="https://dashx.com">Website</a>
    <span> | </span>
    <a href="https://dashxdemo.com">Demos</a>
    <span> | </span>
    <a href="https://docs.dashx.com/developer">Documentation</a>
  </h4>
</div>

<br />

# dashx-php

_DashX SDK for PHP (Experimental)_

## Installation

## Usage
```php
# include composer autoload
require 'vendor/autoload.php';

# import the DashX Client Class
use Dashx\Php\Client;

# create DashX instance
$dashx = new Client(
    'DASHX_PUBLIC_KEY',
    'DASHX_PRIVATE_KEY',
    'DASHX_TARGET_ENVIRONMENT',
    'DASHX_URI'
);

$dashx->deliver('email/forgot-password', [
    'to' => 'youremail@example.com',
    'data' => [
        'token' => 'tokenvalue'
        // ... rest of data payload
    ]
]);
```

## Integration with Laravel

To integrate DashX with Laravel, run the following artisan command to publish the configuration file:

```bash
php artisan vendor:publish --provider="Dashx\Php\Laravel\DashxServiceProvider"
```

Add DashX environment variables with values:

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
    'to' => 'youremail@example.com',
    'data' => [
        'token' => 'tokenvalue'
        // ... rest of data payload
    ]
]);
```
