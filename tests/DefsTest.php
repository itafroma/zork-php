<?php

/**
 * @file
 * Tests for Defs.php.
 */

namespace Itafroma\Zork\Tests;

use function Itafroma\Zork\oget;
use function Itafroma\Zork\oput;

class DefsTest extends ZorkTest
{
    /**
     * Test Itafroma\Zork\oget() with empty property.
     *
     * @dataProvider propertyProvider
     */
    public function testOgetEmptyProperty($property_key)
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $this->assertNull(oget($stub, $property_key));
    }

    /**
     * Test Itafroma\Zork\oget() with extant property.
     *
     * @dataProvider propertyProvider
     */
    public function testOgetExtantProperty($property_key, $property_value)
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $return = oput($stub, $property_key, $property_value);

        $this->assertEquals($property_value, oget($return, $property_key));
    }

    /**
     * Test Itafroma\Zork\oget() with non-objects and non-rooms.
     *
     * @dataProvider propertyProvider
     * @expectedException \InvalidArgumentException
     */
    public function testOgetOther($property_key)
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Prim\Struc')
                     ->getMock();

        oget($stub, $property_key);
    }

    /**
     * Test Itafroma\Zork\oput() with regular objects.
     *
     * @dataProvider propertyProvider
     */
    public function testOputObject($property_key, $property_value)
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $return = oput($stub, $property_key, $property_value);

        $this->assertEquals($stub, $return);
        $this->assertEquals($property_value, $return->oprops[$property_key]);
    }

    /**
     * Test Itafroma\Zork\oput() with rooms.
     *
     * @dataProvider propertyProvider
     */
    public function testOputRoom($property_key, $property_value)
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Room')
                     ->getMock();

        $return = oput($stub, $property_key, $property_value);

        $this->assertEquals($stub, $return);
        $this->assertEquals($property_value, $return->rprops[$property_key]);
    }

    /**
     * Test Itafroma\Zork\oput() with non-objects and non-rooms.
     *
     * @dataProvider propertyProvider
     * @expectedException \InvalidArgumentException
     */
    public function testOputOther($property_key, $property_value)
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Prim\Struc')
                     ->getMock();

        oput($stub, $property_key, $property_value);
    }

    /**
     * Test Itafroma\Zork\oput() with empty object properties and add = false.
     *
     * @dataProvider propertyProvider
     */
    public function testOputNoAdd($property_key, $property_value)
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $return = oput($stub, $property_key, $property_value, false);

        $this->assertArrayNotHasKey($property_key, $return->oprops);
    }
}
