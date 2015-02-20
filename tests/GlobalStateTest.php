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
     */
    public function testGetInstance()
    {
        $this->assertEquals($this->state, GlobalState::getInstance());
    }

    /**
     * Tests Itafroma\Zork\GlobalState::getInstance() with reset.
     */
    public function testGetInstanceReset()
    {
        $this->assertInstanceOf('Itafroma\Zork\GlobalState', GlobalState::getInstance(true));
    }

    /**
     * Tests clone prevention.
     */
    public function testClone()
    {
        $this->assertEquals($this->state, clone $this->state);
    }
}
