<?php

use \JAAulde\IP\V4 as IPv4;

class SubnetMaskTest extends PHPUnit_Framework_TestCase
{
    const ADDRESS_FIRST_DOTTED = '192.168.0.0';
    const ADDRESS_LAST_DOTTED = '192.168.0.255';
    const MASK_DOTTED = '255.255.255.0';
    const BITCOUNT_NETWORK = 24;
    const BITCOUNT_HOST = 8;

    private $subnetMask;

    public function __construct()
    {
        $this->subnetMask = new IPv4\SubnetMask(self::MASK_DOTTED);

        parent::__construct();
    }

    public function testGetHostBitsCount()
    {
        $this->assertEquals(self::BITCOUNT_HOST, $this->subnetMask->getHostBitsCount());
    }

    public function testGetNetworkBitsCount()
    {
        $this->assertEquals(self::BITCOUNT_NETWORK, $this->subnetMask->getNetworkBitsCount());
    }

    public function testGetCIDRPrefix()
    {
        $this->assertEquals(self::BITCOUNT_NETWORK, $this->subnetMask->getCIDRPrefix());
    }

    public function testCalculateCIDRToFit()
    {
        $this->assertEquals(self::BITCOUNT_NETWORK, IPv4\SubnetMask::calculateCIDRToFit(new IPv4\Address(self::ADDRESS_FIRST_DOTTED), new IPv4\Address(self::ADDRESS_LAST_DOTTED)));
    }

    public function testFromCIDRPrefix()
    {
        $this->assertEquals(self::MASK_DOTTED, IPv4\SubnetMask::fromCIDRPrefix(self::BITCOUNT_NETWORK)->get(IPv4\Address::FORMAT_DOTTED_NOTATION));
    }
}
