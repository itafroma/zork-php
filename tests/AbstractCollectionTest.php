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
