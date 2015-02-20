<?php

/**
 * @file
 * Tests Itafroma\Zork\GlobalState.
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\GlobalState;

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
        $this->assertInstanceOf('Itafroma\Zork\GlobalState', GlobalState::getInstance(true));
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
}
