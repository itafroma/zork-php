<?php

/**
 * @file
 * Defines.
 */

use Itafroma\Zork\Defs\Object;
use Itafroma\Zork\Prim\Struc;
use function Itafroma\Zork\make_slot;

// Generalized oflags tester

function trnn($obj, $bit) {
    return ($bit & $obj->oflags) == 0;
}

function rtrnn($rm, $bit) {
    return ($bit & $rm->rbits) == 0;
}

function gtrnn($rm, $bit) {
    return ($bit & $rm->rglobal) == 0;
}

function rtrz($rm, $bit) {
    $rm->rbits &= ($bit ^ -1);

    return $rm->rbits;
}

function trc($obj, $bit) {
    $obj->oflags ^= $bit;

    return $obj->oflags;
}

function trz($obj, $bit) {
    $obj->oflags &= ($bit ^ -1);

    return $obj->oflags;
}

function tro($obj, $bit) {
    $obj->oflags |= $bit;

    return $obj->oflags;
}

function rtro($rm, $bit) {
    $rm->rbits |= $bit;

    return $rm->rbits;
}

function rtrc($rm, $bit) {
    $rm->rbits ^= $bit;

    return $rm->rbits;
}

function atrnn($adv, $bit) {
    return ($bit & $adv->aflags) == 0;
}

function atrz($adv, $bit) {
    $adv->aflags &= ($bit ^ -1);

    return $adv->aflags;
}

function atro($adv, $bit) {
    $adv->aflags |= $bit;

    return $adv->aflags;
}

// Slots for room
make_slot('RVAL', 0);

// Value for entering
make_slot('RGLOBAL', $star_bits);

// Globals for room
flagword(...[
    'RSEENBIT',   // Visited?
    'RLIGHTBIT',  // Endogenous light source?
    'RLANDBIT',   // On land
    'RWATERBIT',  // Water room
    'RAIRBIT',    // Mid-air room
    'RSACREDBIT', // Thief not allowed
    'RFILLBIT',   // Can fill bottle here
    'RMUNGBIT',   // Room has been munged
    'RBUCKBIT',   // This room is a bucket
    'RHOUSEBIT',  // This room is part of the house
    'RENDGAME',   // This room is in the end game
    'RNWALLBIT',  // This room doesn't have walls
]);

/**
 * Retrieves an object property.
 *
 * @param Itafroma\Zork\Prim\Struc $o The object to access.
 * @param mixed                    $p The property to retrieve.
 * @return mixed The property value.
 */
function oget(Struc $o, $p) {
    $v = ($o instanceof Object) ?  $o->oprops : $o->rprops;

    if (empty($v)) {
        return [];
    }

    return isset($v[$p]) ? $v[$p] : [];
}

/**
 * Sets an object property.
 *
 * @param Itafroma\Zork\Prim\Struc $o The object to modify.
 * @param mixed                    $p The property to modify.
 * @param mixed                    $x The value to set.
 */
function oput(Struc $o, $p, $x, $add = false) {
    if ($o instanceof Object) {
        $o->oprops[$p] = $x;
    }
    else {
        $o->rprops[$p] = $x;
    }

    return $o;
}

$rooms = [];
