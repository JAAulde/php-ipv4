<?php

namespace JAAulde\IP\V4;

/**
 * Represents an IP(V4) subnet mask
 *
 * @author Jim Auldridge <auldridgej@gmail.com>
 * @copyright 2006-2015 Jim Auldridge
 * @license MIT
 */
class SubnetMask extends Address {
    /**
     * Retrieve the number of host bits denoted by this mask
     *
     * @return integer
     */
    public function getHostBitsCount () {
        return 32 - $this->getNetworkBitsCount();
    }

    /**
     * Retrieve the number of network bits denoted by this mask
     *
     * @return integer
     */
    public function getNetworkBitsCount () {
        return (int) (32 - log(($this->get() ^ ip2long('255.255.255.255')) + 1, 2));
    }

    /**
     * Alias to \JAAulde\IP\V4\SubnetMask::getNetworkBitsCount
     *
     * @uses \JAAulde\IP\V4\SubnetMask::getNetworkBitsCount
     * @return integer
     */
    public function getCIDRPrefix () {
        return $this->getNetworkBitsCount();
    }

    /**
     * Given two (2) IP (V4) addresses, calculate a CIDR prefix for the network which could contain them both
     *
     * @param \JAAulde\IP\V4\Address $address1
     * @param \JAAulde\IP\V4\Address $address2
     */
    public static function calculateCIDRToFit (Address $address1, Address $address2)  {
        return (int) floor(32 - log(($address1->get() ^ $address2->get()) + 1, 2));
    }

    /**
     * Factory method for producing a SubnetMask instance from a CIDR (slash notation) prefix size
     *
     * @return integer $prefixSize Number of network bits to be represented by the subnet mask
     * @return self
     * @throws Exception
     */
    public static function fromCIDRPrefix ($prefixSize) {
        if (!is_int($prefixSize)) {
            throw new \Exception(__METHOD__ . ' requires first param, $prefixSize, to be an integer');
        }

        if ($prefixSize < 1 || $prefixSize > 31) {
            throw new \Exception(__METHOD__ . ' requires first param, $prefixSize, to be CIDR prefix size with value between 1 and 31 (inclusive)');
        }

        return new self(bindec(str_repeat('1', $prefixSize) .  str_repeat('0', 32 - $prefixSize)));
    }
}