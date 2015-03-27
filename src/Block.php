<?php

namespace JAAulde\IP\V4;

/**
 * Represents a block (network) of IPV4 addresses
 *
 * @author Jim Auldridge <auldridgej@gmail.com>
 * @copyright 2006-2015 Jim Auldridge
 * @license MIT
 */
class Block Extends Range {
    /**
     * @var \JAAulde\IP\V4\SubnetMask $subnetMask The subnetmask of the represented block
     */
    protected $subnetMask;

    /**
     * @param \JAAulde\IP\V4\Address $a1
     * @param \JAAulde\IP\V4\Address|\JAAulde\IP\V4\SubnetMask|integer|string $a2
     * @return self
     * @throws Exception
     *
     * @todo provide param descriptions for $a1 and $a2
     */
    public function __construct (Address $a1, $a2) {
        if (is_int($a2) || is_string($a2)) {
            $a2 = SubnetMask::fromCIDRPrefixSize((int) preg_replace('/[^\d]/', '', (string) $a2));
        }

        if ($a2 instanceof SubnetMask) {
            parent::__construct(self::calculateNetworkAddress($a1, $a2), self::calculateBroadcastAddress($a1, $a2));
            $this->subnetMask = $a2;
        } else if ($a2 instanceof Address) {
            parent::__construct($a1, $a2);
            $this->subnetMask = SubnetMask::fromCIDRPrefixSize($this->getNetworkBitsCount());
        } else {
            throw new Exception('');
        }
    }

    /**
     * Determine if a given IPV4 address is this block's network address (first address in range)
     *
     * @param \JAAulde\IP\V4\Address $address The address we want to know about
     * @return bool
     */
    public function isNetworkAddress (Address $address) {
        return $address->get() === $this->firstAddress->get();
    }

    /**
     * Determine if a given IPV4 address is this block's broadcast address (last address in range)
     *
     * @param \JAAulde\IP\V4\Address $address The address we want to know about
     * @return bool
     */
    public function isBroadcastAddress (Address $address) {
        return $address->get() === $this->lastAddress->get();
    }

    /**
     * Retrieve this block's network address (first address in range)
     *
     * @return \JAAulde\IP\V4\Address
     */
    public function getNetworkAddress () {
        return $this->firstAddress;
    }

    /**
     * Retrieve this block's broadcast address (last address in range)
     *
     * @return \JAAulde\IP\V4\Address
     */
    public function getBroadcastAddress () {
        return $this->lastAddress;
    }

    /**
     * Retrieve this block's subnet mask
     *
     * @return \JAAulde\IP\V4\SubnetMask
     */
    public function getSubnetMask () {
        return $this->subnetMask;
    }

    /**
     * Retrieve the number of host bits represented in this block
     *
     * @return integer
     */
    public function getHostBitsCount () {
        return levenshtein(decbin($this->firstAddress->get()), decbin($this->lastAddress->get()));
    }

    /**
     * Retrieve the number of network bits represented in this block
     *
     * @return integer
     */
    public function getNetworkBitsCount () {
        return 32 - $this->getHostBitsCount();
    }

    /**
     * Retrieve the total number of IPV4 addresses represented in this block
     *
     * @return integer
     */
    public function getAddressCount () {
        return pow(2, $this->getHostBitsCount());
    }

    /**
     * Retrieve the total number of usable IPV4 addresses represented in this block
     *
     * The total number of usable addresses is generally considered to be 2 less than the total number prepresented by the block.
     * This accounts for the fact that the first and last addresses in a block are used for the network and broadcast addresses.
     *
     * @return integer
     */
    public function getUsableAddressCount () {
        return $this->getAddressCount() - 2;
    }

    /**
     * Calculate the network address of a Block given an IPV4 network address and SubnetMask
     *
     * @param \JAAulde\IP\V4\Address $address The IP address from which a network address will be derived
     * @param \JAAulde\IP\V4\SubnetMask $subnetMask The subnet mask (in address form) used in the derivation
     * @return \JAAulde\IP\V4\Address
     */
    public static function calculateNetworkAddress (Address $address, SubnetMask $subnetMask) {
        return new Address($address->get() & $subnetMask->get());
    }

    /**
     * Calculate the broadcast address of a Block given an IPV4 network address and SubnetMask
     *
     * @param \JAAulde\IP\V4\Address $address The IP address from which a broadcast address will be derived
     * @param \JAAulde\IP\V4\SubnetMask $subnetMask The subnet mask (in address form) used in the derivation
     * @return \JAAulde\IP\V4\Address
     */
    public static function calculateBroadcastAddress (Address $address, SubnetMask $subnetMask) {
        return new Address($address->get() | ~$subnetMask->get());
    }
}