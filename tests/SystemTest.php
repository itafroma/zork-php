<?php

/**
 * @file
 * Tests system function replacements.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\State\Oblist;
use function Itafroma\Zork\gassigned;
use function Itafroma\Zork\gval;
use function Itafroma\Zork\insert;
use function Itafroma\Zork\lookup;
use function Itafroma\Zork\setg;

class SystemTest extends ZorkTest
{
    /**
     * Tests Itafroma\Zork\gassigned() when atom exists.
     *
     * @covers ::Itafroma\Zork\gassigned
     * @dataProvider propertyProvider
     */
    public function testGassignedAtomExists($name)
    {
        $this->assertFalse(gassigned($name));
    }

    /**
     * Tests Itafroma\Zork\gassigned() when atom does not exist.
     *
     * @covers ::Itafroma\Zork\gassigned
     * @dataProvider propertyProvider
     */
    public function testGassignedAtomDoesNotExist($name, $value)
    {
        $atoms = $this->container->get('atoms');

        $atoms->set($name, $value);

        $this->assertTrue(gassigned($name));
    }

    /**
     * Tests Itafroma\Zork\gval().
     *
     * @covers ::Itafroma\Zork\gval
     * @dataProvider propertyProvider
     */
    public function testGval($name, $value)
    {
        $atoms = $this->container->get('atoms');

        $atoms->set($name, $value);

        $this->assertEquals($value, gval($name));
    }

    /**
     * Tests Itafroma\Zork\insert().
     *
     * @covers ::Itafroma\Zork\insert
     * @dataProvider oblistPropertyProvider
     */
    public function testInsert($oblist, $name, $value)
    {
        $return = insert($value, $name, $oblist);
        $this->assertEquals($value, $return);
        $this->assertEquals($value, $oblist->get($name));
    }

    /**
     * Tests Itafroma\Zork\insert() when atom is already set.
     *
     * @covers ::Itafroma\Zork\insert
     * @dataProvider oblistPropertyProvider
     * @expectedException Itafroma\Zork\Exception\OblistAtomExistsException
     */
    public function testInsertDuplicate($oblist, $name, $value)
    {
        $oblist->set($name, $value);

        insert($value, $name, $oblist);
    }

    /**
     * Tests Itafroma\Zork\lookup().
     *
     * @covers ::Itafroma\Zork\lookup
     * @dataProvider oblistPropertyProvider
     */
    public function testLookup($oblist, $name, $value)
    {
        $oblist->set($name, $value);

        $this->assertEquals($value, lookup($name, $oblist));
    }

    /**
     * Tests Itafroma\Zork\setg().
     *
     * @covers ::Itafroma\Zork\setg
     * @dataProvider propertyProvider
     */
    public function testSetg($name, $value)
    {
        $atoms = $this->container->get('atoms');

        $this->assertEquals($value, setg($name, $value));
        $this->assertEquals($value, $atoms->get($name));
    }

    /**
     * Provides an oblist and property key-value.
     */
    public function oblistPropertyProvider()
    {
        $properties = $this->propertyProvider();

        foreach ($properties as &$property) {
            array_unshift($property, new Oblist());
        }

        return $properties;
    }
}
