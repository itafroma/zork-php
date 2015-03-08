<?php

/**
 * @file
 * Tests Itafroma\Zork\State\GlobalState.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\State\GlobalState;
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
     */
    public function testConstructor()
    {
        $global_state = $this->createGlobalState();
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

        foreach ($state['atoms'] as $atom => $value) {
            $this->assertArrayHasKey($atom, $return['atoms']);
            $this->assertEquals($value, $return['atoms'][$atom]);
        }
    }

    /**
     * Tests Itafroma\Zork\State\GlobalState::import().
     *
     * @covers Itafroma\Zork\State\GlobalState::import
     * @dataProvider stateProvider
     */
    public function testImport($global_state, $state)
    {
        $global_state->import($state);

        foreach ($state['atoms'] as $atom => $value) {
            $this->assertTrue($global_state->isAssigned($atom));
            $this->assertEquals($value, $global_state->get($atom));
        }
    }

    /**
     * Provides a global state object.
     */
    public function globalStateProvider()
    {
        $global_state = $this->createGlobalState();

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
            $state = [
                'atoms' => [$property[0] => $property[1]],
            ];

            $data[] = [
                $global_states[$i][0],
                $state,
            ];
        }

        return $data;
   }

   /**
    * Creates a GlobalState object, bypassing its singleton checks.
    *
    * @return Itafroma\Zork\State\GlobalState An instance of GlobalState.
    */
   public function createGlobalState()
   {
        $reflected_class = new ReflectionClass(GlobalState::class);
        $global_state = $reflected_class->newInstanceWithoutConstructor();

        $constructor = $reflected_class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($global_state);

        return $global_state;
    }
}
