<?php

namespace JAAulde\IP\V4;

/**
 * Represents an IP(V4) Address
 *
 * @author Jim Auldridge <auldridgej@gmail.com>
 * @copyright 2006-2015 Jim Auldridge
 * @license MIT
 */
class Address {
    const FORMAT_DOTTED_NOTATION = 0;
    const FORMAT_LONG_NOTATION = 1;

    /**
     * @var integer $address The integer value of the address (generally produced by ip2long())
     */
    protected $address;

    /**
     * @param string|int $ip The IP address (in dot-notation-string format or integer) to be represented by the instance
     * @return self
     */
    public function __construct ($ip) {
        $this->setFromMixedSource($ip);
    }

    /**
     * Format and validate for given string or integer formatted IPV4 network address
     *
     * @param string|int $ip The IP address (in dot-notation-string format or integer) to be represented by the instance
     * @return void
     * @throws Exception
     */
    protected function setFromMixedSource ($ip) {
        if (is_int($ip) || is_string($ip)) {
            if (is_int($ip)) {
                /*
                 * Convert int to dotted IP string
                 */
                $ip = long2ip($ip);
            }

            /*
             * We might have been given a string, or we may have converted to string. Attempt to make it an int.
             */
            if (is_string($ip)) {
                $ip = ip2long($ip);
            }
        } else {
            $ip = false;
        }

        /*
         * If given an improper data type, we set the $ip to false.
         * Also, the conversion through ip2long() could result in it becoming false.
         * Either way, we want to bail out.
         */
        if ($ip === false) {
            throw new \Exception(__METHOD__ . ' requires valid IPV4 address string in dot-notation (aaa.bbb.ccc.ddd).');
        }

        $this->set($ip);
    }

    /**
     * Set the value of the address to the integer representation (compensating for addresses which converted to negative on 32bit systems)
     *
     * @param int $ip The IP address, now coerced to integer, to be represented by the instance
     * @return void
     */
    protected function set ($address) {
        /*
            PHP notes that some IP conversions will result in negative numbers on 32Bit architectures ( http://php.net/manual/en/function.ip2long.php#refsect1-function.ip2long-notes )
            We're accounting for this. See note at http://php.net/manual/en/function.ip2long.php#88345 about "Convert IP to unsigned long"
        */
        $this->address = (int) $address + ((int) $address < 0 ? 4294967296 : 0);
    }

    /**
     * Get the integer or dot-notated-string representation of the address
     *
     * @param int $format Whether to return as integer (\JAAulde\IP\V4\Address::FORMAT_LONG_NOTATION) or dot-notation-string (\JAAulde\IP\V4\Address::FORMAT_DOTTED_NOTATION)
     * @return string|int
     */
    public function get ($format = Address::FORMAT_LONG_NOTATION) {
        return $format === Address::FORMAT_DOTTED_NOTATION ? long2ip($this->address) : $this->address;
    }

    /**
     * Output dotted notation on conversion to string
     *
     * @uses \JAAulde\IP\V4\Address::get
     * @return string
     */
    public function __toString () {
        return $this->get(Address::FORMAT_DOTTED_NOTATION);
    }
}
