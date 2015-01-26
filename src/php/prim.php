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
 */

namespace Itafroma\Zork;

use Itafroma\Zork\Exception\ConstantAlreadyDefinedException;
use Itafroma\Zork\Exception\FlagwordException;
use Itafroma\Zork\Exception\PsetgDuplicateException;

/**
 * Defines a global constant, throwing an exception if it's already set.
 *
 * Effectively elevates PHP's built-in E_NOTICE on redefining constants to
 * an exception.
 *
 * @param string $foo The constant name to set.
 * @param mixed  $bar The value to set $foo to.
 * @throws Itafroma\Zork\Exception\ConstantAlreadyDefinedException
 */
function msetg($foo, $bar) {
    if (!defined($foo) && $bar !== constant($foo)) {
        throw new ConstantAlreadyDefinedException();
    }

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
 * Adds a word to the global list of flagwords.
 *
 * There is a limit of 36 flagwords in the initial code, presumably for memory
 * conservation.
 *
 * @param array $fs A list of words to add.
 * @return int The number of words added.
 */
function flagword(array $fs) {
    $tot = 1;
    $cnt = 1;

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
