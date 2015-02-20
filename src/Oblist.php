<?php

/**
 * @file
 * Oblist type.
 */

namespace Itafroma\Zork;

use Itafroma\Zork\Exception\OblistAtomExistsException;

class Oblist
{
    /** @var mixed[] $atoms The atoms within the oblist. */
    private $atoms = [];

    /**
     * Retrieves a value from the oblist.
     *
     * @param string $pname The name of the atom from which to retrieve the value.
     * @return mixed The value if the atom exists in the oblist, false otherwise.
     */
    function get($pname) {
        return isset($this->atoms[$pname]) ? $this->atoms[$pname] : false;
    }

    /**
     * Adds a value to the oblist.
     *
     * @param string $pname The name of the atom to add to the oblist.
     * @param string $value The value to add to the oblist.
     * @return mixed The value added to the oblist.
     * @throws Itafroma\Zork\Exception\OblistAtomExistsException
     */
    function set($pname, $value) {
        if (isset($this->atoms[$pname])) {
            throw new OblistAtomExistsException;
        }

        $this->atoms[$pname] = $value;

        return $value;
    }
}
