<?php

use \JAAulde\IP\V4\Address;
use \JAAulde\IP\V4\SubnetMask;
     
class SubnetMaskTest extends PHPUnit_Framework_TestCase {
    public function testGetHostBitsCount () {
        $subnetMask = new SubnetMask('255.255.255.0');

        $this->assertEquals(8, $subnetMask->getHostBitsCount());
    }

    public function testGetNetworkBitsCount () {
        $subnetMask = new SubnetMask('255.255.255.0');

        $this->assertEquals(24, $subnetMask->getNetworkBitsCount());
    }
    
    public function testGetCIDRPrefix () {
        $subnetMask = new SubnetMask('255.255.255.0');

        $this->assertEquals(24, $subnetMask->getCIDRPrefix());
    }
    
    public function testCalculateCIDRToFit () {
        $firstAddress = new Address('192.168.0.0');
        $lastAddress = new Address('192.168.0.255');

        $cidr = SubnetMask::calculateCIDRToFit($firstAddress, $lastAddress);

        $this->assertEquals(24, $cidr);
    }

    public function testFromCIDRPrefix () {
        $subnetMask = SubnetMask::fromCIDRPrefix(24);

        $this->assertEquals('255.255.255.0', $subnetMask->get(Address::FORMAT_DOTTED_NOTATION));
    }
}