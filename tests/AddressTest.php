<?php

use \JAAulde\IP\V4\Address;
     
class AddressTest extends PHPUnit_Framework_TestCase {
    public function testFromDotted () {
        $address = new Address('192.168.0.1');
        $this->assertEquals(3232235521, $address->get());
        $this->assertEquals(3232235521, $address->get(Address::FORMAT_LONG_NOTATION));
        $this->assertEquals('192.168.0.1', $address->get(Address::FORMAT_DOTTED_NOTATION));
        $this->assertEquals('192.168.0.1', $address . '');
    }

    public function testFromLong () {
        $address = new Address(167901951);
        $this->assertEquals(167901951, $address->get());
        $this->assertEquals(167901951, $address->get(Address::FORMAT_LONG_NOTATION));
        $this->assertEquals('10.1.250.255', $address->get(Address::FORMAT_DOTTED_NOTATION));
        $this->assertEquals('10.1.250.255', $address . '');
    }
}