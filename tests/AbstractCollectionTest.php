<?php

/**
 * @file
 * Tests for abstract collections.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\State\AbstractCollection;

class AbstractCollectionTest extends ZorkTest
{
    /**
     * Tests Itafroma\Zork\AbstractCollection::create().
     *
     * @covers Itafroma\Zork\State\AbstractCollection::create
     * @dataProvider abstractCollectionPropertyProvider
     */
    public function testCreateAtom($abstract_collection, $property_name)
    {
        $return = $abstract_collection->create($property_name);

        $this->assertEquals($return, $this->getPrivateProperty($abstract_collection, 'atoms')[$property_name]);
    }

    /**
     * Tests Itafroma\Zork\State\AbstractCollection::get() when atom exists.
     *
     * @covers Itafroma\Zork\State\AbstractCollection::get
     * @dataProvider abstractCollectionPropertyProvider
     */
    public function testGetAtomExists($abstract_collection, $property_name, $property_value)
    {
        $this->setPrivateProperty($abstract_collection, 'atoms', [$property_name => $property_value]);

        $this->assertEquals($property_value, $abstract_collection->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\AbstractCollection::get() when atom exists.
     *
     * @covers Itafroma\Zork\State\AbstractCollection::get
     * @dataProvider abstractCollectionPropertyProvider
     */
    public function testGetAtomDoesNotExist($abstract_collection, $property_name, $property_value)
    {
        $this->assertFalse($abstract_collection->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\AbstractCollection::set().
     *
     * @covers Itafroma\Zork\State\AbstractCollection::set
     * @dataProvider abstractCollectionPropertyProvider
     */
    public function testSet($abstract_collection, $property_name, $property_value)
    {
        $return = $abstract_collection->set($property_name, $property_value);

        $this->assertEquals($property_value, $return);
        $this->assertEquals($property_value, $abstract_collection->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\AbstractCollection::exists() when the atom exists.
     *
     * @covers Itafroma\Zork\State\AbstractCollection::exists
     * @dataProvider abstractCollectionPropertyProvider
     */
    public function testExistsAtomExists($abstract_collection, $property_name, $property_value)
    {
        $abstract_collection->set($property_name, $property_value);

        $this->assertTrue($abstract_collection->exists($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\AbstractCollection::exists() when the atom does not exist.
     *
     * @covers Itafroma\Zork\State\AbstractCollection::exists
     * @dataProvider abstractCollectionPropertyProvider
     */
    public function testExistsAtomDoesNotExist($abstract_collection, $property_name)
    {
        $this->assertFalse($abstract_collection->exists($property_name));
    }

    /**
     * Provides an abstract collection mock with test properties.
     */
    public function abstractCollectionPropertyProvider()
    {
        $properties = $this->propertyProvider();

        foreach ($properties as &$property)
        {
            $stub = $this->getMockForAbstractClass(AbstractCollection::class);
            array_unshift($property, $stub);
        }

        return $properties;
    }
}
