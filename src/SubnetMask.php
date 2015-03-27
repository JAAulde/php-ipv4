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
    public function getCIDRPrefix () {
        return $this->getNetworkBitsCount();
    }

    /**
     * Retrieve the number of host bits represented in this block
     *
     * @return integer
     */
    public function getHostBitsCount () {
        return 32 - $this->getNetworkBitsCount();
    }

    /**
     * Retrieve the number of network bits represented in this block
     *
     * @return integer
     */
    public function getNetworkBitsCount () {
        return 32 - log(($this->get() ^ ip2long('255.255.255.255')) + 1, 2);
    }

    /**
     * Factory method for producing a SubnetMask instance from a CIDR (slash notation) prefix size
     *
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