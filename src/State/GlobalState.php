<?php

/**
 * @file
 * Global state singleton.
 */

namespace Itafroma\Zork\State;

class GlobalState
{
    /** @var mixed[] $atoms The list of globally assigned atoms. */
    private $atoms = [];

    /** @var Itafroma\Zork\State\OblistCollection $oblistCollcetion A collection of oblists. */
    private $oblistCollection;

    /**
     * Sets initial system state.
     *
     * Declared private to prevent creation outside of singleton mechanics.
     */
    private function __construct(CollectionInterface $oblist_collection)
    {
        $this->setOblistCollection($oblist_collection);
        $this->oblistCollection->create('INITIAL');
    }

    /**
     * Returns a singleton instance of GlobalState.
     */
    public static function getInstance($reset = false)
    {
        static $instance = null;

        if ($instance === null || $reset) {
            $instance = new static(new OblistCollection());
        }

        return $instance;
    }

    /**
     * Prevent shallow-copy cloning.
     */
    public function __clone()
    {
    }

    /**
     * Retrieves a value of a variable within the global state.
     *
     * @param string $name The name of the variable to retrieve.
     * @return mixed The value of the variable if set, false otherwise.
     */
    public function get($name)
    {
        return $this->isAssigned($name) ? $this->atoms[$name] : false;
    }

    /**
     * Assign a value to a variable within the global state.
     *
     * @param string $name The name of the variable to assign the value.
     * @param mixed $value The value to assign.
     * @return mixed The new value of the variable.
     */
    public function set($name, $value)
    {
        $this->atoms[$name] = $value;

        return $value;
    }

    /**
     * Checks to see if a variable is assigned a value within the global state.
     *
     * @param string $name The variable name to check.
     * @return boolean true if the variable is assigned, false otherwise.
     */
    public function isAssigned($name)
    {
        return isset($this->atoms[$name]);
    }

    /**
     * Replaces the current oblist collection.
     *
     * @param Itafroma\Zork\State\OblistCollection $oblist_collection The new oblist collection.
     * @return Itafroma\Zork\State\OblistCollection The oblist collection assigned.
     */
    public function setOblistCollection(OblistCollection $oblist_collection)
    {
        $this->oblistCollection = $oblist_collection;

        return $this->oblistCollection;
    }

    /**
     * Retrieves an oblist by name.
     *
     * @param string $name The name of the oblist to retrieve.
     * @return Itafroma\Zork\State\Oblist The oblist retrieved if it exists, null otherwise.
     */
    public function getOblist($name)
    {
        return $this->oblistCollection->get($name);
    }

    /**
     * Creates a new oblist.
     *
     * @param string $name The name of the oblist to create.
     * @return Itafroma\Zork\State\Oblist The oblist created.
     */
    public function createOblist($name)
    {
        return $this->oblistCollection->create($name);
    }

    /**
     * Export the current state.
     *
     * @return mixed[] An array containing the global state.
     */
    public function export()
    {
        return [
            'atoms' => $this->atoms,
            'oblistCollection' => $this->oblistCollection,
        ];
    }

    /**
     * Import a state.
     *
     * @param mixed[] An array containing the global state.
     * @return void
     */
    public function import(array $state)
    {
        foreach ($state as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
