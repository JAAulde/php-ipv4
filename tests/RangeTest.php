<?php

use \JAAulde\IP\V4 as IPv4;

class RangeTest extends PHPUnit_Framework_TestCase
{
    private $firstAddress;
    private $lastAddress;
    private $range;

    public function __construct()
    {
        $this->firstAddress = new IPv4\Address('192.168.0.0');
        $this->lastAddress = new IPv4\Address('192.168.0.255');

        $this->range = new IPv4\Range($this->firstAddress, $this->lastAddress);

        parent::__construct();
    }

    public function testGetFirstAddress()
    {
        $this->assertEquals($this->firstAddress->get(), $this->range->getFirstAddress()->get());
    }

    public function testGetLastAddress()
    {
        $this->assertEquals($this->lastAddress->get(), $this->range->getLastAddress()->get());
    }

    public function testContains()
    {
        $this->assertEquals(true, $this->range->contains(new IPv4\Address('192.168.0.37')));
        $this->assertEquals(false, $this->range->contains(new IPv4\Address('10.0.0.8')));
    }
}
