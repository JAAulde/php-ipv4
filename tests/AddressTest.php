<?php

use \JAAulde\IP\V4 as IPv4;

class AddressTest extends PHPUnit_Framework_TestCase
{
    const ADDRESS_ONE_DOTTED = '192.168.0.1';
    const ADDRESS_ONE_LONG = 3232235521;
    const ADDRESS_TWO_DOTTED = '10.1.250.255';
    const ADDRESS_TWO_LONG = 167901951;

    public function testFromDotted()
    {
        $address = new IPv4\Address(self::ADDRESS_ONE_DOTTED);

        $this->assertEquals(self::ADDRESS_ONE_LONG, $address->get());
        $this->assertEquals(self::ADDRESS_ONE_LONG, $address->get(IPv4\Address::FORMAT_LONG_NOTATION));
        $this->assertEquals(self::ADDRESS_ONE_DOTTED, $address->get(IPv4\Address::FORMAT_DOTTED_NOTATION));
        $this->assertEquals(self::ADDRESS_ONE_DOTTED, $address . '');
    }

    public function testFromLong()
    {
        $address = new IPv4\Address(self::ADDRESS_TWO_LONG);

        $this->assertEquals(self::ADDRESS_TWO_LONG, $address->get());
        $this->assertEquals(self::ADDRESS_TWO_LONG, $address->get(IPv4\Address::FORMAT_LONG_NOTATION));
        $this->assertEquals(self::ADDRESS_TWO_DOTTED, $address->get(IPv4\Address::FORMAT_DOTTED_NOTATION));
        $this->assertEquals(self::ADDRESS_TWO_DOTTED, $address . '');
    }
}
