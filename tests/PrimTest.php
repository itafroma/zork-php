<?php

/**
 * @file
 * Tests for Prim.php
 */

namespace Itafroma\Zork\Tests;

use Itafroma\Zork\ServiceContainer;
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

    /**
     * Test Itafroma\Zork\msetg().
     *
     * @covers ::Itafroma\Zork\msetg
     * @dataProvider propertyProvider
     */
    public function testMsetg($property_key, $property_value)
    {
        $atoms = $this->container->get('atoms');

        $this->assertEquals($property_value, msetg($property_key, $property_value));
        $this->assertEquals($property_value, $atoms->get($property_key));
    }

    /**
     * Test Itafroma\Zork\msetg() with extant key using the same value.
     *
     * @covers ::Itafroma\Zork\msetg
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
     * @covers ::Itafroma\Zork\msetg
     * @dataProvider propertyProvider
     * @expectedException Itafroma\Zork\Exception\ConstantAlreadyDefinedException
     */
    public function testMsetgExtantKeyDifferentValue($property_key, $property_value1, $property_value2)
    {
        msetg($property_key, $property_value1);
        msetg($property_key, $property_value2);
    }

    /**
     * Test Itafroma\Zork\psetg().
     *
     * @covers ::Itafroma\Zork\psetg
     * @dataProvider propertyProvider
     */
    public function testPsetg($property_key, $property_value)
    {
        $atoms = $this->container->get('atoms');

        $this->assertEquals($property_value, psetg($property_key, $property_value));
        $this->assertContains($property_key, $atoms->get('PURE-LIST'));
    }

    /**
     * Test Itafroma\Zork\psetg() with extant key.
     *
     * @covers ::Itafroma\Zork\psetg
     * @dataProvider propertyProvider
     */
    public function testPsetgExtantKey($property_key, $property_value1, $property_value2)
    {
        $atoms = $this->container->get('atoms');

        psetg($property_key, $property_value1);

        $this->assertEquals($property_value2, psetg($property_key, $property_value2));
        $this->assertContains($property_key, $atoms->get('PURE-LIST'));
    }

    /**
     * Test Itafroma\Zork\psetg() with extant key and PURE-CAREFUL set.
     *
     * @covers ::Itafroma\Zork\psetg
     * @dataProvider propertyProvider
     * @expectedException Itafroma\Zork\Exception\PsetgDuplicateException
     */
    public function testPsetgExtantKeyWithPureCareful($property_key, $property_value1, $property_value2)
    {
        $atoms = $this->container->get('atoms');

        $atoms->set('PURE-CAREFUL', true);

        psetg($property_key, $property_value1);
        psetg($property_key, $property_value2);
    }

    /**
     * Test Itafroma\Zork\flagword().
     *
     * @covers ::Itafroma\Zork\flagword
     * @dataProvider flagProvider
     */
    public function testFlagword($flags)
    {
        $atoms = $this->container->get('atoms');

        $this->assertEquals(self::FLAGSIZEMAX, flagword(...$flags));

        $tot = 1;
        foreach ($flags as $flag) {
            $this->assertEquals($tot, $atoms->get($flag));
            $tot *= 2;
        }
    }

    /**
     * Test Itafroam\Zork\flagword() when GROUP-GLUE flag is set.
     *
     * @covers ::Itafroma\Zork\flagword
     * @dataProvider flagProvider
     */
    public function testFlagwordGroupGlueEnabled($flags)
    {
        $atoms = $this->container->get('atoms');
        $oblists = $this->container->get('oblists');

        $initial_oblist = $oblists->get('INITIAL');
        $initial_oblist->set('GROUP-GLUE', true);

        flagword(...$flags);

        foreach ($flags as $flag) {
            $this->assertFalse($atoms->exists($flag));
        }
    }

    /**
     * Test Itafroma\Zork\flagword() with too many flags.
     *
     * @covers ::Itafroma\Zork\flagword
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
     * @covers ::Itafroma\Zork\newstruc
     * @expectedException \BadFunctionCallException
     */
    public function testNewstruc()
    {
        newstruc('PrimTest-struc', 'PrimTest-type', ...['one', 'two', 'three']);
    }

    /**
     * Test Itafroma\Zork\make_slot() slot creation.
     *
     * @covers ::Itafroma\Zork\make_slot
     * @dataProvider propertyProvider
     */
    public function testMakeSlotCreate($property_key, $property_value)
    {
        $atoms = $this->container->get('atoms');

        $slots = make_slot($property_key, $property_value);

        $this->assertInternalType('callable', $slots[$property_key]);
        $this->assertEquals($slots[$property_key], $atoms->get('SLOTS')[$property_key]);
    }

    /**
     * Test Itafroma\Zork\make_slot() creation on an already-bound name.
     *
     * @covers ::Itafroma\Zork\make_slot
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
     * @covers ::Itafroma\Zork\make_slot
     * @dataProvider objectPropertyProvider
     */
    public function testMakeSlotWrite($struc, $property_key, $property_value)
    {
        $slots = make_slot($property_key, $property_value);
        $return = $slots[$property_key]($struc, $property_value);

        $this->assertEquals($struc, $return);
        $this->assertEquals($property_value, $return->oprops[$property_key]);
    }

    /**
     * Test Itafroma\Zork\make_slot() slot read.
     *
     * @covers ::Itafroma\Zork\make_slot
     * @dataProvider objectPropertyProvider
     */
    public function testMakeSlotRead($struc, $property_key, $property_value)
    {
        $slots = make_slot($property_key, $property_value);

        $slots[$property_key]($struc, $property_value);

        $this->assertEquals($property_value, $slots[$property_key]($struc));
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
        $flags = [];
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
