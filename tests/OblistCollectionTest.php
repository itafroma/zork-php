<?php

/**
 * @file
 * Tests for oblist collections.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\State\Oblist;
use Itafroma\Zork\State\OblistCollection;

class OblistCollectionTest extends ZorkTest
{
    /**
     * Tests Itafroma\Zork\OblistCollection::create().
     *
     * @covers Itafroma\Zork\State\OblistCollection::create
     * @dataProvider oblistCollectionProvider
     */
    public function testCreateOblist($oblist_collection, $name)
    {
        $return = $oblist_collection->create($name);

        $this->assertInstanceOf(Oblist::class, $return);
        $this->assertEquals($return, $this->getPrivateProperty($oblist_collection, 'atoms')[$name]);
    }

    /**
     * Tests Itafroma\Zork\State\OblistCollection::get() when the oblist exists.
     *
     * @covers Itafroma\Zork\State\OblistCollection::get
     * @dataProvider oblistCollectionProvider
     */
    public function testGetOblistExists($oblist_collection, $name, $oblist)
    {
        $this->setPrivateProperty($oblist_collection, 'atoms', [$name => $oblist]);

        $this->assertEquals($oblist, $oblist_collection->get($name));
    }

    /**
     * Tests Itafroma\Zork\State\OblistCollection::get() when the oblist does not exist.
     *
     * @covers Itafroma\Zork\State\OblistCollection::get
     * @dataProvider oblistCollectionProvider
     */
    public function testGetOblistDoesNotExist($oblist_collection, $name)
    {
        $this->assertEquals(null, $oblist_collection->get($name));
    }

    /**
     * Provides data for testing oblist collections.
     */
    public function oblistCollectionProvider()
    {
        return [
            [new OblistCollection(), 'ZorkTest-name', new Oblist()],
        ];
    }
}
