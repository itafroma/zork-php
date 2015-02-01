<?php

/**
 * @file
 * Tests for Prim.php
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\Exception\ConstantAlreadyDefinedException;
use \PHPUnit_Framework_TestCase;
use function Itafroma\Zork\flagword;
use function Itafroma\Zork\make_slot;
use function Itafroma\Zork\msetg;
use function Itafroma\Zork\psetg;
use function Itafroma\Zork\newstruc;

class PrimTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        global $zork;

        foreach (array_keys($zork) as $key) {
            if (strpos($key, 'PrimTest-') === 0) {
                unset($zork[$key]);
            }
        }
    }

    /**
     * Test \Itafroma\Zork\msetg().
     */
    public function testMsetg()
    {
        global $zork;

        $this->assertEquals('value', msetg('PrimTest-key', 'value'));
        $this->assertEquals('value', $zork['PrimTest-key']);
    }

    /**
     * Test \Itafroma\Zork\msetg() with extant key using the same value.
     */
    public function testMsetgExtantKeySameValue()
    {
        msetg('PrimTest-key', 'value');

        try {
            msetg('PrimTest-key', 'value');
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

        msetg('PrimTest-key', 'value1');
        msetg('PrimTest-key', 'value2');
    }

    /**
     * Test \Itafroma\Zork\psetg().
     */
    public function testPsetg()
    {
        global $zork;

        $this->assertEquals('value', psetg('PrimTest-key', 'value'));
        $this->assertContains('PrimTest-key', array_keys($zork['PURE_LIST']));
    }

    /**
     * Test \Itafroma\Zork\psetg() with extant key.
     */
    public function testPsetgExtantKey()
    {
        global $zork;

        psetg('PrimTest-key', 'value1');

        $this->assertEquals('value2', psetg('PrimTest-key', 'value2'));
        $this->assertContains('PrimTest-key', array_keys($zork['PURE_LIST']));
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

        psetg('PrimTest-key', 'value1');
        psetg('PrimTest-key', 'value2');
    }

    /**
     * Test \Itafroma\Zork\flagword().
     */
    public function testFlagword()
    {
        global $zork;

        for ($i = 0; $i < 5; ++$i) {
            $flags[] = 'PrimTest-flag-' . $i;
        }

        $this->assertEquals(6, flagword(...$flags));

        $tot = 1;
        foreach ($flags as $flag) {
            $this->assertEquals($tot, $zork[$flag]);
            $tot *= 2;
        }
    }

    /**
     * Test \Itafroam\Zork\flagword() when GROUP_GLUE flag is set.
     */
    public function testFlagwordGroupGlueEnabled()
    {
        global $zork;

        $zork['OBLIST']['GROUP_GLUE'] = true;

        for ($i = 0; $i < 5; ++$i) {
            $flags[] = 'PrimTest-flag-' . $i;
        }

        flagword(...$flags);

        foreach ($flags as $flag) {
            $this->assertArrayNotHasKey($flag, $zork);
        }
    }

    /**
     * Test \Itafroma\Zork\flagword() with too many flags.
     *
     * @expectedException Itafroma\Zork\Exception\FlagwordException
     */
    public function testFlagwordOverflow()
    {
        for ($i = 0; $i < 37; ++$i) {
            $flags[] = 'PrimTest-flag-' . $i;
        }

        flagword(...$flags);
    }

    /**
     * Test \Itafroma\Zork\newstruc().
     *
     * @expectedException \BadFunctionCallException
     */
    public function testNewstruc()
    {
        newstruc('PrimTest-struc', 'PrimTest-type', ...['one', 'two', 'three']);
    }

    /**
     * Test \Itafroma\Zork\make_slot() slot creation.
     */
    public function testMakeSlotCreate()
    {
        global $zork;

        $slot = make_slot('PrimTest-slot', 'value');

        $this->assertInternalType('callable', $slot);
        $this->assertEquals($slot, $zork['SLOTS']['PrimTest-slot']);
    }

    /**
     * Test \Itafroma\Zork\make_slot() creation on an already-bound name.
     *
     * @expectedException \Itafroma\Zork\Exception\SlotNameAlreadyUsedException
     */
    public function testMakeSlotCreateDuplicate()
    {
        msetg('PrimTest-slot', 'value');
        make_slot('PrimTest-slot', 'value');
    }

    /**
     * Test \Itafroma\Zork\make_slot() slot write.
     */
    public function testMakeSlotWrite()
    {
        $slot = make_slot('PrimTest-slot', 'value');
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $return = $slot($stub, 'value');

        $this->assertEquals($stub, $return);
        $this->assertEquals('value', $return->oprops['PrimTest-slot']);
    }

    /**
     * Test \Itafroma\Zork\make_slot() slot read.
     */
    public function testMakeSlotRead()
    {
        $slot = make_slot('PrimTest-slot', 'value');
        $stub = $this->getMockBuilder('Itafroma\Zork\Defs\Object')
                     ->getMock();

        $slot($stub, 'value');

        $this->assertEquals('value', $slot($stub));
    }
}
