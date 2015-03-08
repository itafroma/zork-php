<?php

/**
 * @file
 * Primitive functions.
 *
 * Overall implementation notes:
 *  - Exceptions are closer to MDL's <ERROR> behavior and are used instead of
 *    trigger_error().
 */

namespace Itafroma\Zork;

use Itafroma\Zork\Exception\ConstantAlreadyDefinedException;
use Itafroma\Zork\Exception\FlagwordException;
use Itafroma\Zork\Exception\PsetgDuplicateException;
use Itafroma\Zork\Exception\SlotNameAlreadyUsedException;
use Itafroma\Zork\Prim\Struc;
use Itafroma\Zork\State\GlobalState;
use \BadFunctionCallException;

/**
 * Defines a global constant, throwing an exception if it's already set.
 *
 * Effectively acts like a declare(), but elevates PHP's built-in E_NOTICE on
 * redefining constants to an exception.
 *
 * @param string $foo The constant name to set.
 * @param mixed  $bar The value to set $foo to.
 * @throws Itafroma\Zork\Exception\ConstantAlreadyDefinedException
 */
function msetg($foo, $bar) {
    if (gassigned($foo) && $bar != gval($foo)) {
        throw new ConstantAlreadyDefinedException();
    }

    return setg($foo, $bar);
}

/**
 * Sets a global variable and adds its value to the global "pure_list" array.
 *
 * @param string $foo The global variable to set.
 * @param mixed  $bar The value to set $foo to.
 * @return mixed The value of $bar.
 * @throws Itafroma\Zork\Exception\PsetgDuplicateException
 */
function psetg($foo, $bar) {
    setg($foo, $bar);
    $pl = gassigned('PURE-LIST') ? gval('PURE-LIST') : setg('PURE-LIST', []);

    if (!in_array($foo, $pl)) {
        array_unshift($pl, $foo);
        setg('PURE-LIST', $pl);
    }
    elseif (gassigned('PURE-CAREFUL') && gval('PURE-CAREFUL')) {
        throw new PsetgDuplicateException();
    }

    return $bar;
}

/**
 * Creates a flag/bit field.
 *
 * In the original source, there's a hard limit of 35 flags that can be added to
 * the flag field, presumably done for memory conservation.
 *
 * @param string ... $fs A list of flags to add.
 * @return int The number of flags added.
 * @throws Itafroma\Zork\Exception\FlagwordException
 */
function flagword(...$fs) {
    $container = ServiceContainer::getContainer();
    $oblists = $container->get('oblists');
    $tot = 1;
    $cnt = 1;

    // Given <FLAGWORD>'s usage in the rest of the original source, this could
    // be simplified to a simple foreach loop. The use of array_walk_recursive()
    // here is to emulate the use of MDL's <MAPF> SUBR in the original source.
    array_walk_recursive($fs, function($f) use ($oblists, &$tot, &$cnt) {
        // It's unknown what the GROUP-GLUE symbol is in the oblist. It appears
        // to be always empty.
        if (is_scalar($f) && !lookup('GROUP-GLUE', $oblists->get('INITIAL'))) {
            msetg($f, $tot);
        }

        $tot *= 2;

        // This appears to be a bug in the original source: the intention seems
        // to be to allow bit fields with up to 36 flags, but because the size
        // is incremented and checked after the last value is set, it only
        // allows 35 flags.
        $cnt++;
        if ($cnt > 36) {
            throw new FlagwordException();
        }
    });

    // Also a bug in the original source: since count is incremented after the
    // last value is added, the reported number of flags added is off by one.
    return $cnt;
}

/**
 * Generates a new structure.
 *
 * This function is intentionally left unimplemented. The purpose of it was to
 * emulate C's struct language feature. In PHP, struct emulation is done through
 * simple class declarations using public properties.
 *
 * @param string    $nam  The name of the structure.
 * @param string    $prim The underlying type of the structure.
 * @param mixed ... $elem A series of elements defining the structure,
 *                        alternating between the element name and the element
 *                        type.
 * @throws \BadFunctionCallException
 */
function newstruc($nam, $prim, ...$elem) {
    throw new BadFunctionCallException('newstruc() has been removed: implement Itafroma\Zork\Prim\Struc instead.');
}

setg('SLOTS', []);

/**
 * "Define a funny slot in an object." (sic)
 *
 * Essentially adds arbitrary named properties to objects. Not sure why they're
 * called "funny slots" yet.
 *
 * Usage:
 *  - Create: make_slot('foo', 'default value');
 *  - Read: $GLOBALS['zork']['foo']($object);
 *  - Update: $GLOBALS['zork']['foo']($object, 'new value');
 *
 * @param string $name The name of the slot to define.
 * @param mixed  $def  The default value of the slot defined.
 * @throws Itafroma\Zork|Exception\SlotNameAlreadyUsedException;
 */
function make_slot($name, $def) {
    // Slot names can only be used once globally.
    // REDEFINE is apparently a local variable in the original <DEFINE> and is
    // never bound.
    if (!gassigned($name) || !empty($redefine)) {
        $slots = gval('SLOTS');
        $slots[$name] = function (Struc $obj, $val = null) use ($name, $def) {
            if (isset($val)) {
                return oput($obj, $name, $val);
            }

            return oget($obj, $name) ?: $def;
        };

        return setg('SLOTS', $slots);
    }

    throw new SlotNameAlreadyUsedException("$name already used.");
}
