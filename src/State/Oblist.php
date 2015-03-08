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
}
