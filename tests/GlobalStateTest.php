<?php

/**
 * @file
 * Tests Itafroma\Zork\State\GlobalState.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\State\GlobalState;
use Itafroma\Zork\State\Oblist;
use Itafroma\Zork\State\OblistCollection;
use \ReflectionClass;
use \ReflectionObject;

class GlobalStateTest extends ZorkTest
{
    /**
     * Tests Itafroma\Zork\State\GlobalState::getInstance().
     *
     * @covers Itafroma\Zork\State\GlobalState::getInstance
     */
    public function testGetInstance()
    {
        $this->assertEquals($this->state, GlobalState::getInstance());
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::getInstance() with reset.
     *
     * @covers Itafroma\Zork\State\GlobalState::getInstance
     */
    public function testGetInstanceReset()
    {
        $this->assertInstanceOf(GlobalState::class, GlobalState::getInstance(true));
    }

    /**
     * Tests clone prevention.
     *
     * @covers Itafroma\Zork\State\GlobalState::__clone
     * @dataProvider globalStateProvider
     */
    public function testClone($global_state)
    {
        $this->assertEquals($global_state, clone $global_state);
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::__construct().
     *
     * @covers Itafroma\Zork\State\GlobalState::__construct
     * @dataProvider globalStateProvider
     */
    public function testConstructor($global_state)
    {
        $return = $global_state->getOblist('INITIAL');

        $this->assertInstanceOf(Oblist::class, $global_state->getOblist('INITIAL'));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::get() when the requested atom exists.
     *
     * @covers Itafroma\Zork\State\GlobalState::get
     * @dataProvider globalStatePropertyProvider
     */
    public function testGetAtomExists($global_state, $property_name, $property_value)
    {
        $this->setPrivateProperty($global_state, 'atoms', [$property_name => $property_value]);

        $this->assertEquals($property_value, $global_state->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::get() when the requested atom does not exist.
     *
     * @covers Itafroma\Zork\State\GlobalState::get
     * @dataProvider globalStatePropertyProvider
     */
    public function testGetAtomDoesNotExist($global_state, $property_name)
    {
        $this->assertFalse($global_state->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::set().
     *
     * @covers Itafroma\Zork\State\GlobalState::set
     * @dataProvider globalStatePropertyProvider
     */
    public function testSet($global_state, $property_name, $property_value)
    {
        $return = $global_state->set($property_name, $property_value);

        $this->assertEquals($property_value, $return);
        $this->assertEquals($property_value, $this->getPrivateProperty($global_state, 'atoms')[$property_name]);
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::isAssigned() when the requested atom exists.
     *
     * @covers Itafroma\Zork\State\GlobalState::isAssigned
     * @dataProvider globalStatePropertyProvider
     */
    public function testIsAssignedAtomExists($global_state, $property_name, $property_value)
    {
        $this->setPrivateProperty($global_state, 'atoms', [$property_name => $property_value]);

        $this->assertTrue($global_state->isAssigned($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::isAssigned() when the requested atom does not exist.
     *
     * @covers Itafroma\Zork\State\GlobalState::isAssigned
     * @dataProvider globalStatePropertyProvider
     */
    public function testIsAssignedAtomDoesNotExist($global_state, $property_name)
    {
        $this->assertFalse($global_state->isAssigned($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::getOblist() when the requested oblist exists.
     *
     * @covers Itafroma\Zork\State\GlobalState::getOblist
     * @dataProvider stateProvider
     */
    public function testGetOblistOblistExists($global_state, $state, $oblist_name)
    {
        $oblist_collection = new OblistCollection();
        $oblist = $oblist_collection->create($oblist_name);
        $this->setPrivateProperty($global_state, 'oblistCollection', $oblist_collection);

        $this->assertEquals($oblist, $global_state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::getOblist() when the requested oblist does not exist.
     *
     * @covers Itafroma\Zork\State\GlobalState::getOblist
     * @dataProvider stateProvider
     */
    public function testGetOblistOblistDoesNotExist($global_state, $state, $oblist_name)
    {
        $this->assertNull($global_state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::createOblist().
     *
     * @covers Itafroma\Zork\State\GlobalState::createOblist
     * @dataProvider stateProvider
     */
    public function testCreateOblist($global_state, $state, $oblist_name)
    {
        $return = $global_state->createOblist($oblist_name);

        $this->assertInstanceOf(Oblist::class, $return);
        $this->assertEquals($return, $global_state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::export().
     *
     * @covers Itafroma\Zork\State\GlobalState::export
     * @dataProvider stateProvider
     */
    public function testExport($global_state, $state)
    {
        $global_state->import($state);

        $return = $global_state->export();

        $this->assertInternalType('array', $return);
        $this->assertArrayHasKey('atoms', $return);
        $this->assertArrayHasKey('oblistCollection', $return);

        foreach ($state['atoms'] as $atom => $value) {
            $this->assertArrayHasKey($atom, $return['atoms']);
            $this->assertEquals($value, $return['atoms'][$atom]);
        }

        $this->assertInstanceOf(OblistCollection::class, $return['oblistCollection']);
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::import().
     *
     * @covers Itafroma\Zork\State\GlobalState::import
     * @dataProvider stateProvider
     */
    public function testImport($global_state, $state, $oblist_name)
    {
        $global_state->import($state);

        foreach ($state['atoms'] as $atom => $value) {
            $this->assertTrue($global_state->isAssigned($atom));
            $this->assertEquals($value, $global_state->get($atom));
        }

        $this->assertEquals($state['oblistCollection']->get($oblist_name), $global_state->getOblist($oblist_name));
    }

    /**
     * Provides a global state object.
     */
    public function globalStateProvider()
    {
        $reflected_class = new ReflectionClass(GlobalState::class);
        $global_state = $reflected_class->newInstanceWithoutConstructor();

        $constructor = $reflected_class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($global_state, new OblistCollection());

        return [[$global_state]];
    }

    /**
     * Provides a global state object with test properties.
     */
    public function globalStatePropertyProvider()
    {
        $global_states = $this->globalStateProvider();
        $properties = $this->propertyProvider();

        foreach ($properties as $i => &$property) {
            array_unshift($property, $global_states[$i][0]);
        }

        return $properties;
    }


    /**
     * Provides a default state.
     */
    public function stateProvider()
    {
        $global_states = $this->globalStateProvider();
        $properties = $this->propertyProvider();
        $data = [];

        foreach ($properties as $i => $property) {
            $oblist_collection = new OblistCollection();
            $oblist_collection->create($property[0]);

            $state = [
                'atoms' => [$property[0] => $property[1]],
                'oblistCollection' => $oblist_collection,
            ];

            $data[] = [
                $global_states[$i][0],
                $state,
                $property[0],
            ];
        }

        return $data;
   }
}
