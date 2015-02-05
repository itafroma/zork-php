<?php

/**
 * @file
 * Tests for Defs.php.
 */

namespace Itafroma\Zork\Tests;

use \PHPUnit_Framework_TestCase;
use function Itafroma\Zork\oget;
use function Itafroma\Zork\oput;

class DefsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        global $zork;

        foreach (array_keys($zork) as $key) {
            if (strpos($key, 'DefsTest-') === 0) {
                unset($zork[$key]);
            }
        }
    }

    /**
     * Test Itafroma\Zork\oget() with empty property.
     */
    public function testOgetEmptyProperty()
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $this->assertNull(oget($stub, 'DefsTest-property'));
    }

    /**
     * Test Itafroma\Zork\oget() with extant property.
     */
    public function testOgetExtantProperty()
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $return = oput($stub, 'DefsTest-property', 'value');

        $this->assertEquals('value', oget($return, 'DefsTest-property'));
    }

    /**
     * Test Itafroma\Zork\oget() with non-objects and non-rooms.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testOgetOther()
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Prim\Struc')
                     ->getMock();

        oget($stub, 'DefsTest-property');
    }

    /**
     * Test Itafroma\Zork\oput() with regular objects.
     */
    public function testOputObject()
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $return = oput($stub, 'DefsTest-property', 'value');

        $this->assertEquals($stub, $return);
        $this->assertEquals('value', $return->oprops['DefsTest-property']);
    }

    /**
     * Test Itafroma\Zork\oput() with rooms.
     */
    public function testOputRoom()
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Room')
                     ->getMock();

        $return = oput($stub, 'DefsTest-property', 'value');

        $this->assertEquals($stub, $return);
        $this->assertEquals('value', $return->rprops['DefsTest-property']);
    }

    /**
     * Test Itafroma\Zork\oput() with non-objects and non-rooms.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testOputOther()
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Prim\Struc')
                     ->getMock();

        oput($stub, 'DefsTest-property', 'value');
    }

    /**
     * Test Itafroma\Zork\oput() with empty object properties and add = false.
     */
    public function testOputNoAdd()
    {
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $return = oput($stub, 'DefsTest-property', 'value', false);

        $this->assertArrayNotHasKey('DefsTest-property', $return->oprops);
    }
}
