<?php

/**
 * Base test class.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\ServiceContainer;
use Itafroma\Zork\Struc\Object;
use Itafroma\Zork\Struc\Room;
use \PHPUnit_Framework_TestCase;
use \ReflectionObject;

abstract class ZorkTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = ServiceContainer::getContainer(true);
    }

    /**
     * Gets the value of a private property.
     */
    public function getPrivateProperty($object, $property)
    {
        $reflected_object = new ReflectionObject($object);
        $reflected_property = $reflected_object->getProperty($property);
        $reflected_property->setAccessible(true);

        return $reflected_property->getValue($object);
    }
    /**
     * Sets the value of a private property.
     */
    public function setPrivateProperty($object, $property, $value)
    {
        $reflected_object = new ReflectionObject($object);
        $reflected_property = $reflected_object->getProperty($property);
        $reflected_property->setAccessible(true);
        $reflected_property->setValue($object, $value);
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
            array_unshift($property, $this->getMockBuilder('Itafroma\Zork\Struc\StrucInterface')->getMock());
        }

        return $properties;
    }
}
