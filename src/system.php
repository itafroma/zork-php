<?php

/**
 * @file
 * System-level functions.
 */

namespace Itafroma\Zork;

use Itafroma\Zork\State\GlobalState;
use Itafroma\Zork\State\Oblist;
use Itafroma\Zork\Exception\OblistAtomExistsException;

/**
 * Checks to see if a variable is assigned a value within the global state.
 *
 * @param string $atom The variable name to check.
 * @return boolean true if the variable is assigned, false otherwise.
 */
function gassigned($atom) {
    $state = GlobalState::getInstance();

    return $state->isAssigned($atom);
}

/**
 * Retrieves a value of a variable within the global state.
 *
 * @param string $atom The name of the variable to retrieve.
 * @return mixed The value of the variable if set, false otherwise.
 */
function gval($atom)
{
    $state = GlobalState::getInstance();

    return $state->get($atom);
}

/**
 * Adds a value to an oblist.
 *
 * If the atom already exists within the oblists, an exception is thrown.
 *
 * @param string $value The value to add to the oblist.
 * @param string $pname The name of the atom to add to the oblist.
 * @param Itafroma\Zork\State\Oblist $oblist The oblist to modify.
 * @return mixed The value added to the oblist.
 * @throws Itafroma\Zork\Exception\OblistAtomExistsException
 */
function insert($value, $pname, Oblist $oblist) {
    if ($oblist->exists($pname)) {
        throw new OblistAtomExistsException;
    }

    return $oblist->set($pname, $value);
}

/**
 * Retrieves a value from an oblist.
 *
 * @param string $pname The name of the atom from which to retrieve the value.
 * @param Itafroma\Zork\State\Oblist $oblist The oblist to check.
 * @return mixed The value if the atom exists in the oblist, false otherwise.
 */
function lookup($pname, Oblist $oblist) {
    return is_null($oblist) ? false : $oblist->get($pname);
}

/**
 * Assign a value to a variable within the global state.
 *
 * @param string $atom The name of the variable to assign the value.
 * @param mixed $any The value to assign.
 * @return mixed The new value of the variable.
 */
function setg($atom, $any) {
    $state = GlobalState::getInstance();

    return $state->set($atom, $any);
}
