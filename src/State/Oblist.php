<?php

/**
 * @file
 * Oblist type.
 */

namespace Itafroma\Zork\State;

use Itafroma\Zork\Exception\OblistAtomExistsException;

class Oblist extends AbstractCollection
{
    /**
     * Creates an atom within the oblist with a null value.
     *
     * @param string $pname The name of the atom to create.
     * @return mixed The value of the atom created.
     */
    public function create($pname)
    {
        if (!isset($this->atoms[$pname])) {
            $this->atoms[$pname] = null;
        }

        return $this->atoms[$pname];
    }

    /**
     * Retrieves a value from the oblist.
     *
     * @param string $pname The name of the atom from which to retrieve the value.
     * @return mixed The value if the atom exists in the oblist, false otherwise.
     */
    public function get($pname)
    {
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
    public function set($pname, $value)
    {
        if (isset($this->atoms[$pname])) {
            throw new OblistAtomExistsException;
        }

        $this->atoms[$pname] = $value;

        return $value;
    }
}
