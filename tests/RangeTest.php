<?php

use \JAAulde\IP\V4\Address;
use \JAAulde\IP\V4\Range;
     
class RangeTest extends PHPUnit_Framework_TestCase {
    public function testGetFirstAddress () {
        $firstAddress = new Address('192.168.0.0');
        $lastAddress = new Address('192.168.0.255');

        $range = new Range($firstAddress, $lastAddress);

        $this->assertEquals($firstAddress->get(), $range->getFirstAddress()->get());
    }

    public function testGetLastAddress () {
        $firstAddress = new Address('192.168.0.0');
        $lastAddress = new Address('192.168.0.255');

        $range = new Range($firstAddress, $lastAddress);

        $this->assertEquals($lastAddress->get(), $range->getLastAddress()->get());
    }

    public function testContains () {
        $firstAddress = new Address('192.168.0.0');
        $lastAddress = new Address('192.168.0.255');

        $containsAddress = new Address('192.168.0.37');
        $notContainsAddress = new Address('10.0.0.8');

        $range = new Range($firstAddress, $lastAddress);

        $this->assertEquals(true, $range->contains($containsAddress));
        $this->assertEquals(false, $range->contains($notContainsAddress));
    }
}