<?php

/**
 * @file
 * Tests for Prim.php
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\Exception\ConstantAlreadyDefinedException;
use \PHPUnit_Framework_TestCase;
use function Itafroma\Zork\msetg;
use function Itafroma\Zork\psetg;
use function Itafroma\Zork\newstruc;

class PrimTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test \Itafroma\Zork\msetg().
     */
    public function testMsetg()
    {
        global $zork;

        msetg('foo', 'bar');
        $this->assertEquals('bar', $zork['foo']);
    }

    /**
     * Test \Itafroma\Zork\msetg() with extant key using the same value.
     */
    public function testMsetgExtantKeySameValue()
    {
        msetg('foo', 'bar');

        try {
            msetg('foo', 'bar');
        }
        catch (ConstantAlreadyDefinedException $e) {
            $this->fail('Itafroma\Zork\Exception\ConstantAlreadyDefinedException should not be thrown when global value is reassigned the same value.');
        }
    }

    /**
     * Test \Itafroma\Zork\msetg() with extant key using a different value.
     *
     * @expectedException Itafroma\Zork\Exception\ConstantAlreadyDefinedException
     */
    public function testMsetgExtantKeyDifferentValue()
    {
        global $zork;

        msetg('foo', 'bar');
        msetg('foo', 'baz');
    }

    /**
     * Test \Itafroma\Zork\psetg().
     */
    public function testPsetg()
    {
        global $zork;

        $this->assertEquals('bar', psetg('foo', 'bar'));
        $this->assertContains('foo', array_keys($zork['PURE_LIST']));
    }

    /**
     * Test \Itafroma\Zork\psetg() with extant key.
     */
    public function testPsetgExtantKey()
    {
        global $zork;

        psetg('foo', 'bar');

        $this->assertEquals('baz', psetg('foo', 'baz'));
        $this->assertContains('foo', array_keys($zork['PURE_LIST']));
    }

    /**
     * Test \Itafroma\Zork\psetg() with extant key and PURE_CAREFUL set.
     *
     * @expectedException Itafroma\Zork\Exception\PsetgDuplicateException
     */
    public function testPsetgExtantKeyWithPureCareful()
    {
        global $zork;

        $zork['PURE_CAREFUL'] = true;

        psetg('foo', 'bar');
        psetg('foo', 'baz');
    }

    /**
     * Test \Itafroma\Zork\newstruc().
     *
     * @expectedException \BadFunctionCallException
     */
    public function testNewstruc()
    {
        newstruc('foo', 'bar', ...['one', 'two', 'three']);
    }
}
