Stripe-api [![Build Status](https://travis-ci.org/mapado/stripe-api.svg?branch=master)](https://travis-ci.org/mapado/stripe-api)
================

High level api for [stripe-php](https://github.com/stripe/stripe-php)

## Installation
Install with [composer](http://www.getcomposer.org):
```sh
composer require "mapado/stripe:0.*"
```

## Usage

Initialize the API:
```php
$privateKey = 'pk_.....';
$stripeApi = new \Mapado\Stripe\StripeApi($privateKey);
```

call the wanted method:
```php
$invoice = $stripeApi->getInvoice('invoice_id');
```

Those methods returns Proxy classes. You can chain method like this:
```php
$subscription = $invoice->getSubscription();
```

## Status
The current package is in early development. A very small number of methods is implemented.

Feel free to fork it and create pull requests to add your logic.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b4746ea8-de0a-45db-b735-883429f82252/small.png)](https://insight.sensiolabs.com/projects/b4746ea8-de0a-45db-b735-883429f82252)
