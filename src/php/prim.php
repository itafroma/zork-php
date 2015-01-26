<?php

/**
 * @file
 * Primitive functions.
 *
 * Implementation notes:
 *  - $GLOBALS is used over the global keyword to prevent name collisions.
 *  - Exceptions are closer to MDL's <ERROR> behavior and are used instead of
 *    trigger_error().
 *  - It's unknown what the GROUP_GLUE symbol is in the oblist. It appears to be
 *    always empty.
 *  - <NEWSTRUC> has been removed entirely and calls to it are replaced with
 *    class declarations. It was used to emulate a C-like struct that's not
 *    necessary given PHP's weak typing (and the existence of classes).
 */

namespace Itafroma\Zork;

use Itafroma\Zork\Exception\ConstantAlreadyDefinedException;
use Itafroma\Zork\Exception\FlagwordException;
use Itafroma\Zork\Exception\PsetgDuplicateException;
use \BadFunctionCallException;

/**
 * Defines a global constant, throwing an exception if it's already set.
 *
 * Effectively elevates PHP's built-in E_NOTICE on redefining constants to
 * an exception and adds the constant to PHP's list of global variables.
 *
 * @param string $foo The constant name to set.
 * @param mixed  $bar The value to set $foo to.
 * @throws Itafroma\Zork\Exception\ConstantAlreadyDefinedException
 */
function msetg($foo, $bar) {
    if (!defined($foo) && $bar !== constant($foo)) {
        throw new ConstantAlreadyDefinedException();
    }

    $GLOBALS[$foo] = $bar;
    define($foo, $bar);
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
    $pl = [];
    $GLOBALS[$foo] = $bar;

    if (!isset($GLOBALS['PURE_LIST'])) {
        $GLOBALS['PURE_LIST'] = [];
    }
    $pl = $GLOBALS['PURE_LIST'];

    if (!in_array($GLOBALS[$foo], $pl)) {
        $pl = array_merge($GLOBALS[$foo], $pl);
        $GLOBALS['PURE_LIST'] = $pl;
    }
    elseif (defined('PURE_CAREFUL') && PURE_CAREFUL) {
        throw new PsetgDuplicateException();
    }

    return $bar;
}

/**
 * Creates a flag/bit field.
 *
 * In the original source, there's a hard limit of 36 flag that can be added to
 * the flag field, presumably done for memory conservation.
 *
 * @param string ... $fs A list of flags to add.
 * @return int The number of flags added.
 */
function flagword(...$fs) {
    $tot = 1;
    $cnt = 1;

    // Given <FLAGWORD>'s usage in the rest of the original source, this could
    // be simplified to a simple foreach loop. The use of array_walk_recursive()
    // here is to emulate the use of MDL's <MAPF> SUBR in the original source.
    array_walk_recursive($fs, function($f) {
        if (!isset($GLOBALS['OBLIST']['GROUP_GLUE'])) {
            msetg($f, $tot);
        }

        $tot *= 2;

        $cnt++;
        if($cnt > 36) {
            throw new FlagwordException();
        }
    });

    return $cnt;
}

function newstruc($nam, $prim, ...$elem) {
    throw new BadFunctionCallException('newstruc() has been removed: use classes instead.');
}
