<?php

/**
 * @file
 * Base implementation of state collections.
 */

namespace Itafroma\Zork\State;

abstract class AbstractCollection implements CollectionInterface
{
    protected $atoms = [];

    /**
     * Creates a new atom within the collection.
     *
     * @param string $atom The name of the atom to create.
     * @return mixed The atom created.
     */
    abstract public function create($name);

    /**
     * Retrieves an atom by name.
     *
     * @param string $name The name of the atom to retrieve.
     * @return mixed The atom retrieved if it exists, false otherwise.
     */
    public function get($name)
    {
        return isset($this->atoms[$name]) ? $this->atoms[$name] : false;
    }
}
