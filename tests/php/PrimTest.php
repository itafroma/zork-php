<?php

/**
 * @file
 * Tests for Prim.php
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\Exception\ConstantAlreadyDefinedException;
use \PHPUnit_Framework_TestCase;
use function Itafroma\Zork\msetg;

class PrimTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test \Itafroma\Zork\msetg().
     */
    public function testMsetg()
    {
        global $zork;

        msetg('foo', 'bar');
        $this->assertEquals($zork['foo'], 'bar');

        try {
            msetg('foo', 'bar');
        }
        catch (ConstantAlreadyDefinedException $e) {
            $this->fail('Itafroma\Zork\Exception\ConstantAlreadyDefinedException should not be thrown when global value is reassigned the same value.');
        }

        $this->setExpectedException('Itafroma\Zork\Exception\ConstantAlreadyDefinedException');
        msetg('foo', 'baz');
    }
}
