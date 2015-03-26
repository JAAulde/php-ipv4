<?php

namespace JAAulde\IP\V4;

/**
 * Create an instance of \JAAulde\IP\V4\Block with a given IPV4 address and CIDR prefix
 *
 * @author Jim Auldridge <auldridgej@gmail.com>
 * @copyright 2006-2015 Jim Auldridge
 * @license MIT
 */
class BlockFromCIDRNotation extends BlockFromSubnetMask {
    /**
     * Create an (extended) instance of \JAAulde\IP\V4\Block with a given IPV4 address and CIDR (slash notation) prefix length
     *
     * @param \JAAulde\IP\V4\Address $address The IP address from which a network and broadcast will be derived
     * @param integer $prefixSize CIDR slash notation of subnet mask
     * @return self
     */
    public function __construct (Address $address, $prefixSize) {
        parent::__construct($address, SubnetMask::fromCIDRPrefixSize($prefixSize));
    }
}