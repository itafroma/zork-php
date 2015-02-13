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
     * @dataProvider objectPropertyProvider
     */
    public function testOgetEmptyProperty($struc, $property_key)
    {
        $this->assertNull(oget($struc, $property_key));
    }

    /**
     * Test Itafroma\Zork\oget() with extant property.
     *
     * @dataProvider objectPropertyProvider
     */
    public function testOgetExtantProperty($struc, $property_key, $property_value)
    {
        $return = oput($struc, $property_key, $property_value);

        $this->assertEquals($property_value, oget($return, $property_key));
    }

    /**
     * Test Itafroma\Zork\oget() with non-objects and non-rooms.
     *
     * @dataProvider strucPropertyProvider
     * @expectedException \InvalidArgumentException
     */
    public function testOgetOther($struc, $property_key)
    {
        oget($struc, $property_key);
    }

    /**
     * Test Itafroma\Zork\oput() with regular objects.
     *
     * @dataProvider objectPropertyProvider
     */
    public function testOputObject($struc, $property_key, $property_value)
    {
        $return = oput($struc, $property_key, $property_value);

        $this->assertEquals($struc, $return);
        $this->assertEquals($property_value, $return->oprops[$property_key]);
    }

    /**
     * Test Itafroma\Zork\oput() with rooms.
     *
     * @dataProvider roomPropertyProvider
     */
    public function testOputRoom($struc, $property_key, $property_value)
    {
        $return = oput($struc, $property_key, $property_value);

        $this->assertEquals($struc, $return);
        $this->assertEquals($property_value, $return->rprops[$property_key]);
    }

    /**
     * Test Itafroma\Zork\oput() with non-objects and non-rooms.
     *
     * @dataProvider strucPropertyProvider
     * @expectedException \InvalidArgumentException
     */
    public function testOputOther($struc, $property_key, $property_value)
    {
        oput($struc, $property_key, $property_value);
    }

    /**
     * Test Itafroma\Zork\oput() with empty object properties and add = false.
     *
     * @dataProvider objectPropertyProvider
     */
    public function testOputNoAdd($struc, $property_key, $property_value)
    {
        $return = oput($struc, $property_key, $property_value, false);

        $this->assertArrayNotHasKey($property_key, $return->oprops);
    }
}
