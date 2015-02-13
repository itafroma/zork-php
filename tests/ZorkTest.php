<?php

/**
 * Base test class.
 */

namespace Itafroma\Zork\Tests;

use \PHPUnit_Framework_TestCase;

abstract class ZorkTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        global $zork;

        // Clear global state.
        foreach ($this->propertyProvider() as $property) {
            $key = $property[0];
            unset($zork[$key]);
        }
    }

    /**
     * Key-value properties used for testing functions modifying global state.
     */
    public function propertyProvider()
    {
        return [
            ['ZorkTest-property', 'value1', 'value2'],
        ];
    }
}
