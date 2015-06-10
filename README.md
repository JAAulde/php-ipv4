# php-ipv4
PHP classes for working with IPV4 addresses and networks.

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/JAAulde/php-ipv4.svg?branch=master)](https://travis-ci.org/JAAulde/php-ipv4)
[![Coverage Status](https://coveralls.io/repos/JAAulde/php-ipv4/badge.svg?branch=master)](https://coveralls.io/r/JAAulde/php-ipv4?branch=master)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/JAAulde/php-ipv4.svg?style=flat-square)](https://packagist.org/packages/JAAulde/php-ipv4)
[![Total Downloads](https://img.shields.io/packagist/dt/JAAulde/php-ipv4.svg?style=flat-square)](https://packagist.org/packages/JAAulde/php-ipv4)

## installation
### [composer](https://getcomposer.org)
```bash
composer require jaaulde/php-ipv4
```

## Examples
### Hard Coded Whitelist
```php
<?php

use \JAAulde\IP\V4 as IPv4;

// Define a whitelist - IPs from this address Block are allowed in
$allowed_client_network = new IPv4\Block('192.168.0.0/24');

// Read the IP address of the current client into an Address instance
$client_address = new IPv4\Address($_SERVER['REMOTE_ADDR']);

// Check that the whitelisted address block contains the current client IP
if ($allowed_client_network->contains($client_address)) {
    // Client is on the whitelist, let them in
} else {
    // Client is NOT on the whitelist, deny access
}
```
