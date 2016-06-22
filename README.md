# php-ipv4

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/JAAulde/php-ipv4.svg?branch=master)](https://travis-ci.org/JAAulde/php-ipv4)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JAAulde/php-ipv4/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/JAAulde/php-ipv4/?branch=master)
[![Coverage Status](https://coveralls.io/repos/JAAulde/php-ipv4/badge.svg?branch=master)](https://coveralls.io/r/JAAulde/php-ipv4?branch=master)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/JAAulde/php-ipv4.svg?style=flat-square)](https://packagist.org/packages/JAAulde/php-ipv4)
[![Total Downloads](https://img.shields.io/packagist/dt/JAAulde/php-ipv4.svg?style=flat-square)](https://packagist.org/packages/JAAulde/php-ipv4)

PHP classes for working with IPV4 addresses and networks.

## Install
### via [composer](https://getcomposer.org)
```bash
$ composer require jaaulde/php-ipv4
```

## Change log
Please see [CHANGELOG](CHANGELOG.md) for information about what has changed recently.

## Testing
``` bash
$ composer test
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Usage
...

## Examples
_**Warning:** These examples are simplified and without context. Usage of unfiltered/unvalidated request variables (such as from `$_POST`) in these examples is not an endoresement or recommendation to do so in your own projects._

- [Hard Coded Whitelist](#user-content-hard-coded-whitelist)
- [DB Driven Address Blocking](#user-content-db-driven-address-blocking)

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

### DB Driven Address Blocking
Many PHP applications allow administrators to blacklists for ranges of abusive IPs to ease moderator work loads. Most of these applications use overly complicated db schemas to store the IP addresses which represent the ranges, and use a series of string manipulations to determine if IPs fall into the banned ranges. By applying the actual math and logic involved in the IPv4 address scheme, `php-ipv4` radically simplifies all of this, and provides a more reliable filtering of IPs.

An example administrator interface may offer a form something like this for entering ranges to ban:
```html
<form action="ip-ban.php" method="POST">
    <p>
        <label for="first_address">Enter the <em>first</em> address of the range to block:</label>
        <br>
        <input type="text" id="first_address">
    </p>
    <p>
        <label for="last_address">Enter the <em>last</em> address of the range to block:</label>
        <br>
        <input type="text" id="last_address">
    </p>
    <input type="submit" value="Block">
</form>
```

The receiving PHP script, `ip-ban.php` would then do something like this:
```php
<?php

use \JAAulde\IP\V4 as IPv4;

try {
    // The Address constructor will fail if first parameter is not in correct format, so
    // form validation is as simple as trying to create instances with the POSted data
    $first_address = new IPv4\Address($_POST['first_address']);
    $last_address = new IPv4\Address($_POST['last_address']);
    
    // The Range constructor will fail if the addresses are not properly ordered, so form validation
    // is as simple as trying to create an instance with the previously created Address instances
    $blacklisted_range = new IPv4\Range($first_address, $last_address);
} catch (Exception) {
    // Invalid data POSTed, take user back to form with error info
    exit();
}
```

Supposing the `Range` instance was properly created, we could move on to store it. This is where `php-ipv4` really begins to stand out. Rather than store some string representation of the IP in a database for later retrieval and comparison gyrations, `php-ipv4` lets us easily store the integer representation of the IP in database columns for later querying using a `BETWEEN` clause.

Given a database table with integer values representing the first and last IP addresses in blocked ranges:
```sql
CREATE TABLE `ip_range_blocks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_address` int(10) unsigned NOT NULL,
  `last_address` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

We could ask the range for the integer value of its first and last addresses and store those.
```php
<?php

$query = sprintf(
    'INSERT INTO `ip_range_blocks` (`first_address`, `last_address`) VALUES (%s, %s);',
    $blacklisted_range->getFirstAddress()->get(),
    $blacklisted_range->getLastAddress()->get()
);
```

Finally, when we want to check if a client's IP address is one which is blocked, we can simply query like so:
```php
<?php

use \JAAulde\IP\V4 as IPv4;

$client_address = new IPv4\Address($_SERVER['REMOTE_ADDR']);

$query = sprintf(
    'SELECT COUNT(*) FROM `ip_range_blocks` WHERE %s BETWEEN `first_address` AND `last_address`;',
    $client_address->get()
);
```

**If the above query returns a count greater than 0, you know that the client's IP address is on a ban list.** Otherwise, they're allowed to continue. It's that simple--there is no need ask for the actual data in the rows or iterate and compare.

That's not all, though. A better admin interface would allow you to specify any IP and a subnet mask in dot notation or CIDR. `php-ipv4` can make that a breeze as well. Given the same DB schema as before and a form that would POST `address` and `mask` to `ip-ban.php`, you could simply:
```php
<?php

use \JAAulde\IP\V4 as IPv4;

// The same form validation note from the previous example still stands
$blacklisted_network_block = new IPv4\Block($_POST['address'], $_POST['mask']);

$query = sprintf(
    'INSERT INTO `ip_range_blocks` (`first_address`, `last_address`) VALUES (%s, %s);',
    $blacklisted_network_block->getNetworkAddress()->get(),
    $blacklisted_network_block->getBroadcastAddress()->get()
);
```

The check for whether a client IP was blacklisted would then be identical to the already given example for such.
