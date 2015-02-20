<?php

/**
 * @file
 * Tests system function replacements.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\Oblist;
use function Itafroma\Zork\gassigned;
use function Itafroma\Zork\gval;
use function Itafroma\Zork\insert;
use function Itafroma\Zork\lookup;
use function Itafroma\Zork\setg;

class SystemTest extends ZorkTest
{
    /**
     * Tests Itafroma\Zork\gassigned().
     *
     * @dataProvider propertyProvider
     */
    function testGassigned($name, $value)
    {
        $this->assertFalse(gassigned($name));

        $this->state->set($name, $value);
        $this->assertTrue(gassigned($name));
    }

    /**
     * Tests Itafroma\Zork\gval().
     *
     * @dataProvider propertyProvider
     */
    function testGval($name, $value)
    {
        $this->state->set($name, $value);
        $this->assertEquals($value, gval($name));
    }

    /**
     * Tests Itafroma\Zork\insert().
     *
     * @dataProvider oblistPropertyProvider
     */
    function testInsert($oblist, $name, $value)
    {
        $return = insert($value, $name, $oblist);
        $this->assertEquals($value, $return);
        $this->assertEquals($value, $oblist->get($name));
    }

    /**
     * Tests Itafroma\Zork\insert() when atom is already set.
     *
     * @dataProvider oblistPropertyProvider
     * @expectedException Itafroma\Zork\Exception\OblistAtomExistsException
     */
    function testInsertDuplicate($oblist, $name, $value)
    {
        $oblist->set($name, $value);

        insert($value, $name, $oblist);
    }

    /**
     * Tests Itafroma\Zork\lookup().
     *
     * @dataProvider oblistPropertyProvider
     */
    function testLookup($oblist, $name, $value)
    {
        $oblist->set($name, $value);

        $this->assertEquals($value, lookup($name, $oblist));
    }

    /**
     * Tests Itafroma\Zork\setg().
     *
     * @dataProvider propertyProvider
     */
    function testSetg($name, $value)
    {
        $this->assertEquals($value, setg($name, $value));
        $this->assertEquals($value, $this->state->get($name));
    }

    /**
     * Provides an oblist and property key-value.
     */
    function oblistPropertyProvider()
    {
        $properties = $this->propertyProvider();

        foreach ($properties as &$property) {
            array_unshift($property, new Oblist());
        }

        return $properties;
    }
}
