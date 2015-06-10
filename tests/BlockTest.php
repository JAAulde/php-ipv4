<?php

use \JAAulde\IP\V4 as IPv4;

class BlockTest extends PHPUnit_Framework_TestCase
{
    private $network_address;
    private $broadcast_address;
    private $subnet_mask;
    private $false_assertion_address;
    private $address_count = 256;
    private $useable_address_count = 254;

    public function __construct()
    {
        $this->network_address = new IPv4\Address('192.168.0.0');
        $this->broadcast_address = new IPv4\Address('192.168.0.255');
        $this->subnet_mask = new IPv4\SubnetMask('255.255.255.0');
        $this->false_assertion_address = new IPv4\Address('192.168.0.1');

        parent::__construct();
    }

    private function makeAssertionsOnBlock($block)
    {
        $this->assertEquals(true, $block instanceof IPv4\Block);
        $this->assertEquals(true, $block->isNetworkAddress($this->network_address));
        $this->assertEquals(false, $block->isNetworkAddress($this->false_assertion_address));
        $this->assertEquals(true, $block->isBroadcastAddress($this->broadcast_address));
        $this->assertEquals(false, $block->isBroadcastAddress($this->false_assertion_address));

        $this->assertEquals($this->network_address->get(), $block->getNetworkAddress()->get());
        $this->assertEquals($this->broadcast_address->get(), $block->getBroadcastAddress()->get());
        $this->assertEquals($this->subnet_mask->get(), $block->getSubnetMask()->get());
        $this->assertEquals($this->address_count, $block->getAddressCount());
        $this->assertEquals($this->useable_address_count, $block->getUsableAddressCount());
    }

    public function testFromDottedSlash()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block('192.168.0.0/24'));
    }

    public function testFromDottedDotted()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block('192.168.0.0', '255.255.255.0'));
    }
    
    public function testFromDottedSlashCIDRStr()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block('192.168.0.0', '/24'));
    }

    public function testFromDottedCIDRStr()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block('192.168.0.0', '24'));
    }

    public function testFromDottedCIDRInt()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block('192.168.0.0', 24));
    }

    public function testFromInstanceCIDRInt()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block(new IPv4\Address('192.168.0.0'), 24));
    }

    public function testFromInstanceDotted()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block(new IPv4\Address('192.168.0.0'), '255.255.255.0'));
    }

    public function testFromInstanceInstance()
    {
        $this->makeAssertionsOnBlock(new IPv4\Block(new IPv4\Address('192.168.0.0'), new IPv4\SubnetMask('255.255.255.0')));
    }
}
