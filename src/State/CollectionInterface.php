<?php

/**
 * @file
 * Interface for state collections.
 */

namespace Itafroma\Zork\State;

interface CollectionInterface
{
    /**
     * Creates a new atom within the collection.
     *
     * @param string $atom The name of the atom to create.
     * @return mixed The atom created.
     */
    public function create($name);

    /**
     * Retrieves an atom by name.
     *
     * @param string $name The name of the atom to retrieve.
     * @return mixed The atom retrieved if it exists, false otherwise.
     */
    public function get($name);

    /**
     * Adds a value to the collection.
     *
     * @param string $name The name of the atom to add to the collection.
     * @param string $value The value to add to the collection.
     * @return mixed The value added to the collection.
     */
    public function set($name, $value);

    /**
     * Checks to see if an atom already exists within the collection.
     *
     * @param string $name The name of the atom.
     * @return boolean True if the atom exists, false otherwise.
     */
    public function exists($name);
}
