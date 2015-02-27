<?php

/**
 * @file
 * Contains the state of oblists.
 */

namespace Itafroma\Zork\State;

class OblistCollection extends AbstractCollection
{
    /**
     * Creates a new oblist within the collection.
     *
     * @param string $name The name of the oblist to create.
     * @return Itafroma\Zork\State\Oblist The oblist created.
     */
    public function create($name)
    {
        $this->atoms[$name] = new Oblist();

        return $this->atoms[$name];
    }

    /**
     * Retrieves an oblist by name.
     *
     * @param string $name The name of the oblist to retrieve.
     * @return Itafroma\Zork\State\Oblist The oblist retrieved if it exists, null otherwise.
     */
    public function get($name)
    {
        return isset($this->atoms[$name]) ? $this->atoms[$name] : null;
    }
}
