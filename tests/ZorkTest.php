<?php

/**
 * Base test class.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\GlobalState;
use Itafroma\Zork\Defs\Object;
use Itafroma\Zork\Defs\Room;
use \PHPUnit_Framework_TestCase;

abstract class ZorkTest extends PHPUnit_Framework_TestCase
{
    protected static $stateData = null;
    protected $state;

    public static function setUpBeforeClass()
    {
        $instance = GlobalState::getInstance();
        self::$stateData = $instance->export();
    }

    public function setUp()
    {
        $this->state = GlobalState::getInstance();
        $this->state->import(self::$stateData);
    }

    public function tearDown()
    {
        // Reset global state to original values.
        $this->state = GlobalState::getInstance();
        $this->state->import(self::$stateData);
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

    /**
     * Provides a room struc and a mock property.
     */
    public function roomPropertyProvider()
    {
        $properties = $this->propertyProvider();

        foreach ($properties as &$property) {
            array_unshift($property, new Room());
        }

        return $properties;
    }

    /**
     * Provides an object struc and a mock property.
     */
    public function objectPropertyProvider()
    {
        $properties = $this->propertyProvider();

        foreach ($properties as &$property) {
            array_unshift($property, new Object());
        }

        return $properties;
    }

    /**
     * Provides a generic struc and a mock property.
     */
    public function strucPropertyProvider()
    {
        $properties = $this->propertyProvider();

        foreach ($properties as &$property) {
            array_unshift($property, $this->getMockBuilder('Itafroma\Zork\Prim\Struc')->getMock());
        }

        return $properties;
    }
}
