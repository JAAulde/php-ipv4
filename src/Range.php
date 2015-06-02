<?php

namespace JAAulde\IP\V4;

/**
 * Represents a range of IPV4 addresses
 *
 * @author Jim Auldridge <auldridgej@gmail.com>
 * @copyright 2006-2015 Jim Auldridge
 * @license MIT
 */
class Range
{
    /**
     * @var \JAAulde\IP\V4\Address $firstAddress The first address in the range being represented
     */
    protected $firstAddress;
    /**
     * @var \JAAulde\IP\V4\Address $lastAddress The last address in the range being represented
     */
    protected $lastAddress;

    /**
     * @param \JAAulde\IP\V4\Address $firstAddress The first address of the range being created
     * @param \JAAulde\IP\V4\Address $lastAddress The last address of the range being created
     * @return self
     * @throws Exception
     */
    public function __construct(Address $firstAddress, Address $lastAddress)
    {
        if ($firstAddress->get() > $lastAddress->get()) {
            throw new \Exception(__METHOD__ . ' first param, $firstAddress, cannot be higher address than second param, $lastAddress');
        }

        $this->firstAddress = $firstAddress;
        $this->lastAddress = $lastAddress;
    }

    /**
     * Determine if a given address is contained within the range
     *
     * @param \JAAulde\IP\V4\Address $address The address we want to know about
     * @return bool
     */
    public function contains(Address $address)
    {
        $addressValue = $address->get();

        return $addressValue >= $this->firstAddress->get() && $addressValue <= $this->lastAddress->get();
    }

    /**
     * Retrieve the first address in the range
     *
     * @return \JAAulde\IP\V4\Address
     */
    public function getFirstAddress()
    {
        return $this->firstAddress;
    }

    /**
     * Retrieve the last address in the range
     *
     * @return \JAAulde\IP\V4\Address
     */
    public function getLastAddress()
    {
        return $this->lastAddress;
    }
}
