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

    /**
     * Sets initial system state.
     *
     * Declared private to prevent creation outside of singleton mechanics.
     */
    private function __construct()
    {
    }

    /**
     * Returns a singleton instance of GlobalState.
     */
    public static function getInstance($reset = false)
    {
        static $instance = null;

        if ($instance === null || $reset) {
            $instance = new static();
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
     * Export the current state.
     *
     * @return mixed[] An array containing the global state.
     */
    public function export()
    {
        return [
            'atoms' => $this->atoms,
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
