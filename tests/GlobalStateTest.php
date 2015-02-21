<?php

/**
 * @file
 * Tests Itafroma\Zork\GlobalState.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\GlobalState;
use Itafroma\Zork\Oblist;
use \ReflectionClass;
use \ReflectionObject;

class GlobalStateTest extends ZorkTest
{
    /**
     * Tests Itafroma\Zork\GlobalState::getInstance().
     *
     * @covers Itafroma\Zork\GlobalState::getInstance
     */
    public function testGetInstance()
    {
        $this->assertEquals($this->state, GlobalState::getInstance());
    }

    /**
     * Tests Itafroma\Zork\GlobalState::getInstance() with reset.
     *
     * @covers Itafroma\Zork\GlobalState::getInstance
     */
    public function testGetInstanceReset()
    {
        $this->assertInstanceOf(GlobalState::class, GlobalState::getInstance(true));
    }

    /**
     * Tests clone prevention.
     *
     * @covers Itafroma\Zork\GlobalState::__clone
     */
    public function testClone()
    {
        $this->assertEquals($this->state, clone $this->state);
    }

    /**
     * Tests Itafroma\Zork\GlobalState::__construct().
     *
     * @covers Itafroma\Zork\GlobalState::__construct
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
     * Tests Itafroma\Zork\GlobalState::get() when the requested atom exists.
     *
     * @covers Itafroma\Zork\GlobalState::get
     * @dataProvider propertyProvider
     */
    public function testGetAtomExists($property_name, $property_value)
    {
        $reflected_object = new ReflectionObject($this->state);
        $reflected_atoms = $reflected_object->getProperty('atoms');
        $reflected_atoms->setAccessible(true);
        $reflected_atoms->setValue($this->state, [$property_name => $property_value]);

        $this->assertEquals($property_value, $this->state->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\GlobalState::get() when the requested atom does not exist.
     *
     * @covers Itafroma\Zork\GlobalState::get
     * @dataProvider propertyProvider
     */
    public function testGetAtomDoesNotExist($property_name)
    {
        $this->assertFalse($this->state->get($property_name));
    }

    /**
     * Tests Itafroma\Zork\GlobalState::set().
     *
     * @covers Itafroma\Zork\GlobalState::set
     * @dataProvider propertyProvider
     */
    public function testSet($property_name, $property_value)
    {
        $reflected_object = new ReflectionObject($this->state);
        $reflected_atoms = $reflected_object->getProperty('atoms');
        $reflected_atoms->setAccessible(true);

        $return = $this->state->set($property_name, $property_value);

        $this->assertEquals($property_value, $return);
        $this->assertEquals($property_value, $reflected_atoms->getValue($this->state)[$property_name]);
    }

    /**
     * Tests Itafroma\Zork\GlobalState::isAssigned() when the requested atom exists.
     *
     * @covers Itafroma\Zork\GlobalState::isAssigned
     * @dataProvider propertyProvider
     */
    public function testIsAssignedAtomExists($property_name, $property_value)
    {
        $reflected_object = new ReflectionObject($this->state);
        $reflected_atoms = $reflected_object->getProperty('atoms');
        $reflected_atoms->setAccessible(true);
        $reflected_atoms->setValue($this->state, [$property_name => $property_value]);

        $this->assertTrue($this->state->isAssigned($property_name));
    }

    /**
     * Tests Itafroma\Zork\GlobalState::isAssigned() when the requested atom does not exist.
     *
     * @covers Itafroma\Zork\GlobalState::isAssigned
     * @dataProvider propertyProvider
     */
    public function testIsAssignedAtomDoesNotExist($property_name)
    {
        $this->assertFalse($this->state->isAssigned($property_name));
    }

    /**
     * Tests Itafroma\Zork\GlobalState::getOblist() when the requested oblist exists.
     *
     * @covers Itafroma\Zork\GlobalState::getOblist
     * @dataProvider propertyProvider
     */
    public function testGetOblistOblistExists($oblist_name)
    {
        $oblist = new Oblist();

        $reflected_object = new ReflectionObject($this->state);
        $reflected_atoms = $reflected_object->getProperty('oblists');
        $reflected_atoms->setAccessible(true);
        $reflected_atoms->setValue($this->state, [$oblist_name => $oblist]);

        $this->assertEquals($oblist, $this->state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\GlobalState::getOblist() when the requested oblist does not exist.
     *
     * @covers Itafroma\Zork\GlobalState::getOblist
     * @dataProvider propertyProvider
     */
    public function testGetOblistOblistDoesNotExist($oblist_name)
    {
        $this->assertNull($this->state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\GlobalState::createOblist().
     *
     * @covers Itafroma\Zork\GlobalState::createOblist
     * @dataProvider propertyProvider
     */
    public function testCreateOblist($oblist_name)
    {
        $return = $this->state->createOblist($oblist_name);

        $this->assertInstanceOf(Oblist::class, $return);
        $this->assertEquals($return, $this->state->getOblist($oblist_name));
    }

    /**
     * Tests Itafroma\Zork\GlobalState::export().
     *
     * @covers Itafroma\Zork\GlobalState::export
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
     * Tests Itafroma\Zork\GlobalState::import().
     *
     * @covers Itafroma\Zork\GlobalState::import
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
