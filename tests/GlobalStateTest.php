<?php

/**
 * @file
 * Tests Itafroma\Zork\State\GlobalState.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\State\GlobalState;
use Itafroma\Zork\State\Oblist;
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
     */
    public function testClone()
    {
        $this->assertEquals($this->state, clone $this->state);
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::__construct().
     *
     * @covers Itafroma\Zork\State\GlobalState::__construct
     */
    public function testConstructor()
    {
        $prophecy = $this->prophesize(GlobalState::class);
        $prophecy->createOblist('INITIAL')->shouldBeCalled();
        $mock = $prophecy->reveal();

        $reflected_class = new ReflectionClass(GlobalState::class);
        $constructor = $reflected_class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($mock);
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::get() when the requested atom exists.
     *
     * @covers Itafroma\Zork\State\GlobalState::get
     * @dataProvider propertyProvider
     */
    public function testGetAtomExists($property_name, $property_value)
    {
        $this->setPrivateProperty($this->state, 'atoms', [$property_name => $property_value]);

        $this->assertEquals($property_value, $this->state->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::get() when the requested atom does not exist.
     *
     * @covers Itafroma\Zork\State\GlobalState::get
     * @dataProvider propertyProvider
     */
    public function testGetAtomDoesNotExist($property_name)
    {
        $this->assertFalse($this->state->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::set().
     *
     * @covers Itafroma\Zork\State\GlobalState::set
     * @dataProvider propertyProvider
     */
    public function testSet($property_name, $property_value)
    {
        $return = $this->state->set($property_name, $property_value);

        $this->assertEquals($property_value, $return);
        $this->assertEquals($property_value, $this->getPrivateProperty($this->state, 'atoms')[$property_name]);
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::isAssigned() when the requested atom exists.
     *
     * @covers Itafroma\Zork\State\GlobalState::isAssigned
     * @dataProvider propertyProvider
     */
    public function testIsAssignedAtomExists($property_name, $property_value)
    {
        $this->setPrivateProperty($this->state, 'atoms', [$property_name => $property_value]);

        $this->assertTrue($this->state->isAssigned($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::isAssigned() when the requested atom does not exist.
     *
     * @covers Itafroma\Zork\State\GlobalState::isAssigned
     * @dataProvider propertyProvider
     */
    public function testIsAssignedAtomDoesNotExist($property_name)
    {
        $this->assertFalse($this->state->isAssigned($property_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::getOblist() when the requested oblist exists.
     *
     * @covers Itafroma\Zork\State\GlobalState::getOblist
     * @dataProvider propertyProvider
     */
    public function testGetOblistOblistExists($oblist_name)
    {
        $oblist = new Oblist();
        $this->setPrivateProperty($this->state, 'oblists', [$oblist_name => $oblist]);

        $this->assertEquals($oblist, $this->state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::getOblist() when the requested oblist does not exist.
     *
     * @covers Itafroma\Zork\State\GlobalState::getOblist
     * @dataProvider propertyProvider
     */
    public function testGetOblistOblistDoesNotExist($oblist_name)
    {
        $this->assertNull($this->state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::createOblist().
     *
     * @covers Itafroma\Zork\State\GlobalState::createOblist
     * @dataProvider propertyProvider
     */
    public function testCreateOblist($oblist_name)
    {
        $return = $this->state->createOblist($oblist_name);

        $this->assertInstanceOf(Oblist::class, $return);
        $this->assertEquals($return, $this->state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::export().
     *
     * @covers Itafroma\Zork\State\GlobalState::export
     * @dataProvider stateProvider
     */
    public function testExport($state)
    {
        foreach($state['atoms'] as $atom => $value) {
            $this->state->set($atom, $value);
        }

        foreach (array_keys($state['oblists']) as $oblist) {
            $this->state->createOblist($oblist);
        }

        $return = $this->state->export();

        $this->assertInternalType('array', $return);
        $this->assertArrayHasKey('atoms', $return);
        $this->assertArrayHasKey('oblists', $return);

        foreach ($state['atoms'] as $atom => $value) {
            $this->assertArrayHasKey($atom, $return['atoms']);
            $this->assertEquals($value, $return['atoms'][$atom]);
        }

        foreach (array_keys($state['oblists']) as $oblist) {
            $this->assertArrayHasKey($oblist, $return['oblists']);
            $this->assertInstanceOf(Oblist::class, $return['oblists'][$oblist]);
        }
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::import().
     *
     * @covers Itafroma\Zork\State\GlobalState::import
     * @dataProvider stateProvider
     */
    public function testImport($state)
    {
        $this->state->import($state);

        foreach ($state['atoms'] as $atom => $value) {
            $this->assertTrue($this->state->isAssigned($atom));
            $this->assertEquals($value, $this->state->get($atom));
        }

        foreach ($state['oblists'] as $oblist => $value) {
            $this->assertEquals($value, $this->state->getOblist($oblist));
        }
    }

    /**
     * Provides a default state.
     */
    public function stateProvider()
    {
        $properties = $this->propertyProvider();
        $states = [];

        foreach ($properties as $property) {
            $states[] = [[
                'atoms' => [$property[0] => $property[1]],
                'oblists' => [$property[0] => new Oblist()],
            ]];
        }

        return $states;
   }
}
