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

class PrimTest extends ZorkTest
{
    const FLAGSIZEMAX = 36;

    public function tearDown()
    {
        global $zork;

        parent::tearDown();

        // Clear global flag state.
        foreach ($this->flagOverflowProvider() as $flags) {
            foreach ($flags[0] as $flag) {
                unset($zork[$flag]);
            }
        }
    }

    /**
     * Test Itafroma\Zork\msetg().
     *
     * @dataProvider propertyProvider
     */
    public function testMsetg($property_key, $property_value)
    {
        global $zork;

        $this->assertEquals($property_value, msetg($property_key, $property_value));
        $this->assertEquals($property_value, $zork[$property_key]);
    }

    /**
     * Test Itafroma\Zork\msetg() with extant key using the same value.
     *
     * @dataProvider propertyProvider
     */
    public function testMsetgExtantKeySameValue($property_key, $property_value)
    {
        msetg($property_key, $property_value);

        try {
            msetg($property_key, $property_value);
        }
        catch (ConstantAlreadyDefinedException $e) {
            $this->fail('Itafroma\Zork\Exception\ConstantAlreadyDefinedException should not be thrown when global value is reassigned the same value.');
        }
    }

    /**
     * Test Itafroma\Zork\msetg() with extant key using a different value.
     *
     * @dataProvider propertyProvider
     * @expectedException Itafroma\Zork\Exception\ConstantAlreadyDefinedException
     */
    public function testMsetgExtantKeyDifferentValue($property_key, $property_value1, $property_value2)
    {
        global $zork;

        msetg($property_key, $property_value1);
        msetg($property_key, $property_value2);
    }

    /**
     * Test Itafroma\Zork\psetg().
     *
     * @dataProvider propertyProvider
     */
    public function testPsetg($property_key, $property_value)
    {
        global $zork;

        $this->assertEquals($property_value, psetg($property_key, $property_value));
        $this->assertContains($property_key, array_keys($zork['PURE_LIST']));
    }

    /**
     * Test Itafroma\Zork\psetg() with extant key.
     *
     * @dataProvider propertyProvider
     */
    public function testPsetgExtantKey($property_key, $property_value1, $property_value2)
    {
        global $zork;

        psetg($property_key, $property_value1);

        $this->assertEquals($property_value2, psetg($property_key, $property_value2));
        $this->assertContains($property_key, array_keys($zork['PURE_LIST']));
    }

    /**
     * Test Itafroma\Zork\psetg() with extant key and PURE_CAREFUL set.
     *
     * @dataProvider propertyProvider
     * @expectedException Itafroma\Zork\Exception\PsetgDuplicateException
     */
    public function testPsetgExtantKeyWithPureCareful($property_key, $property_value1, $property_value2)
    {
        global $zork;

        $zork['PURE_CAREFUL'] = true;

        psetg($property_key, $property_value1);
        psetg($property_key, $property_value2);
    }

    /**
     * Test Itafroma\Zork\flagword().
     *
     * @dataProvider flagProvider
     */
    public function testFlagword($flags)
    {
        global $zork;

        $this->assertEquals(self::FLAGSIZEMAX, flagword(...$flags));

        $tot = 1;
        foreach ($flags as $flag) {
            $this->assertEquals($tot, $zork[$flag]);
            $tot *= 2;
        }
    }

    /**
     * Test Itafroam\Zork\flagword() when GROUP_GLUE flag is set.
     *
     * @dataProvider flagProvider
     */
    public function testFlagwordGroupGlueEnabled($flags)
    {
        global $zork;

        $zork['OBLIST']['GROUP_GLUE'] = true;

        flagword(...$flags);

        foreach ($flags as $flag) {
            $this->assertArrayNotHasKey($flag, $zork);
        }
    }

    /**
     * Test Itafroma\Zork\flagword() with too many flags.
     *
     * @dataProvider flagOverflowProvider
     * @expectedException Itafroma\Zork\Exception\FlagwordException
     */
    public function testFlagwordOverflow($flags)
    {
        flagword(...$flags);
    }

    /**
     * Test Itafroma\Zork\newstruc().
     *
     * @expectedException \BadFunctionCallException
     */
    public function testNewstruc()
    {
        newstruc('PrimTest-struc', 'PrimTest-type', ...['one', 'two', 'three']);
    }

    /**
     * Test Itafroma\Zork\make_slot() slot creation.
     *
     * @dataProvider propertyProvider
     */
    public function testMakeSlotCreate($property_key, $property_value)
    {
        global $zork;

        $slot = make_slot($property_key, $property_value);

        $this->assertInternalType('callable', $slot);
        $this->assertEquals($slot, $zork['SLOTS'][$property_key]);
    }

    /**
     * Test Itafroma\Zork\make_slot() creation on an already-bound name.
     *
     * @dataProvider propertyProvider
     * @expectedException Itafroma\Zork\Exception\SlotNameAlreadyUsedException
     */
    public function testMakeSlotCreateDuplicate($property_key, $property_value)
    {
        msetg($property_key, $property_value);
        make_slot($property_key, $property_value);
    }

    /**
     * Test Itafroma\Zork\make_slot() slot write.
     *
     * @dataProvider objectPropertyProvider
     */
    public function testMakeSlotWrite($struc, $property_key, $property_value)
    {
        $slot = make_slot($property_key, $property_value);
        $return = $slot($struc, $property_value);

        $this->assertEquals($struc, $return);
        $this->assertEquals($property_value, $return->oprops[$property_key]);
    }

    /**
     * Test Itafroma\Zork\make_slot() slot read.
     *
     * @dataProvider objectPropertyProvider
     */
    public function testMakeSlotRead($struc, $property_key, $property_value)
    {
        $slot = make_slot($property_key, $property_value);

        $slot($struc, $property_value);

        $this->assertEquals($property_value, $slot($struc));
    }


    /**
     * Generate a list of flags for a bit field.
     *
     * @param int $size The size of the bit field.
     * @return array The generated list of flags.
     */
    protected function generateFlags($size)
    {
        // Subtracting one is done to account for the off-by-one bug replicated
        // in \Itafroma\Zork\flagword().
        for ($i = 0; $i < $size - 1; ++$i) {
            $flags[] = 'ZorkTest-flag-' . $i;
        }

        return $flags;
    }

    /**
     * Provide a list of flags up to the maximum bit field size.
     */
    public function flagProvider()
    {
        return [[$this->generateFlags(self::FLAGSIZEMAX)]];
    }

    /**
     * Provide a list of flags beyond the maximum bit field size.
     */
    public function flagOverflowProvider()
    {
        return [[$this->generateFlags(self::FLAGSIZEMAX + 10)]];
    }
}
