<?php

namespace JAAulde\IP\V4;

/**
 * Create an instance of \JAAulde\IP\V4\Block with a given IPV4 address and SubnetMask
 *
 * @author Jim Auldridge <auldridgej@gmail.com>
 * @copyright 2006-2015 Jim Auldridge
 * @license MIT
 */
class BlockFromSubnetMask extends Block {
    /**
     * @param \JAAulde\IP\V4\Address $address The IP address from which a network and broadcast will be derived
     * @param \JAAulde\IP\V4\SubnetMask $subnetMask The subnet mask (in address form) used in the derivation
     * @return self
     */
    public function __construct (Address $address, SubnetMask $subnetMask) {
        $this->subnetMask = $subnetMask;

        parent::__construct(self::calculateNetworkAddress($address, $subnetMask), self::calculateBroadcastAddress($address, $subnetMask));
    }
}