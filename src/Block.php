<?php

namespace JAAulde\IP\V4;

/**
 * Represents a block (network) of IPV4 addresses
 *
 * @author Jim Auldridge <auldridgej@gmail.com>
 * @copyright 2006-2015 Jim Auldridge
 * @license MIT
 */
class Block {
    /**
     * @var \JAAulde\IP\V4\Address $networkAddress The network address (first IP in range) of the block being represented
     */
    protected $networkAddress;
    /**
     * @var \JAAulde\IP\V4\Address $broadcastAddress The broadcast address (last IP in range) of the block being represented
     */
    protected $broadcastAddress;
    /**
     * @var \JAAulde\IP\V4\SubnetMask $$subnetMask The subnetmask of the represented block
     */
    protected $subnetMask;

    /**
     * @param \JAAulde\IP\V4\Address $networkAddress The network address (first IP in range) of the block being created
     * @param \JAAulde\IP\V4\Address $broadcastAddress The broadcast address (last IP in range) of the block being created
     * @return self
     * @throws Exception
     */
    public function __construct (Address $networkAddress, Address $broadcastAddress) {
        if ($networkAddress->get() > $broadcastAddress->get()) {
            throw new \Exception(__METHOD__ . ' first param, $networkAddress, cannot be higher address than second param, $broadcastAddress');
        }

        $this->networkAddress = $networkAddress;
        $this->broadcastAddress = $broadcastAddress;

        if (!isset($this->subnetMask)) {
            $this->subnetMask = SubnetMask::fromCIDRPrefixSize($this->getNetworkBitsCount());
        }
    }

    /**
     * Determine if a given IPV4 network address is contained within this block
     *
     * @param \JAAulde\IP\V4\Address $address The address we want to know about
     * @return bool
     */
    public function contains (Address $address) {
        $addressValue = $address->get();

        return $addressValue >= $this->networkAddress->get() && $addressValue <= $this->broadcastAddress->get();
    }

    /**
     * Determine if a given IPV4 network address is this block's network address (first address in range)
     *
     * @param \JAAulde\IP\V4\Address $address The address we want to know about
     * @return bool
     */
    public function isNetworkAddress (Address $address) {
        return $address->get() === $this->networkAddress->get();
    }

    /**
     * Determine if a given IPV4 network address is this block's broadcast address (last address in range)
     *
     * @param \JAAulde\IP\V4\Address $address The address we want to know about
     * @return bool
     */
    public function isBroadcastAddress (Address $address) {
        return $address->get() === $this->broadcastAddress->get();
    }

    /**
     * Retrieve this block's network address (first address in range)
     *
     * @return \JAAulde\IP\V4\Address
     */
    public function getNetworkAddress () {
        return $this->networkAddress;
    }

    /**
     * Retrieve this block's broadcast address (last address in range)
     *
     * @return \JAAulde\IP\V4\Address
     */
    public function getBroadcastAddress () {
        return $this->broadcastAddress;
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
        return levenshtein(decbin($this->networkAddress->get()), decbin($this->broadcastAddress->get()));
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