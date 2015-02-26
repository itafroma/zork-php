<?php

/**
 * @file
 * Tests for Itafroma\Zork\State\Oblist
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\State\Oblist;

class OblistTest extends ZorkTest
{
    /**
     * Tests Itafroma\Zork\State\Oblist::get() when the requested object exists.
     *
     * @covers Itafroma\Zork\State\Oblist::get
     * @dataProvider oblistPropertyProvider
     */
    public function testGetObjectExists($oblist, $property_name, $property_value)
    {
        $this->setPrivateProperty($oblist, 'atoms', [$property_name => $property_value]);

        $this->assertEquals($property_value, $oblist->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\Oblist::get() when the requested object does not exist.
     *
     * @covers Itafroma\Zork\State\Oblist::get
     * @dataProvider oblistPropertyProvider
     */
    public function testGetObjectDoesNotExist($oblist, $property_name)
    {
        $this->assertFalse($oblist->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\Oblist::set() when the requested object already exists.
     *
     * @covers Itafroma\Zork\State\Oblist::set
     * @dataProvider oblistPropertyProvider
     * @expectedException Itafroma\Zork\Exception\OblistAtomExistsException
     */
    public function testSetObjectExists($oblist, $property_name, $property_value)
    {
        $oblist->set($property_name, $property_value);
        $oblist->set($property_name, $property_value);
    }

    /**
     * Tests Itafroma\Zork\State\Oblist::set() when the requested object does not already exist.
     *
     * @covers Itafroma\Zork\State\Oblist::set
     * @dataProvider oblistPropertyProvider
     */
    public function testSetObjectDoesNotExist($oblist, $property_name, $property_value)
    {
        $return = $oblist->set($property_name, $property_value);

        $this->assertEquals($property_value, $return);
        $this->assertEquals($property_value, $this->getPrivateProperty($oblist, 'atoms')[$property_name]);
    }


    /**
     * Provides an oblist with a test property and value.
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
