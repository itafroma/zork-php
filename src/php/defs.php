<?php

/**
 * @file
 * Defines.
 */

use Itafroma\Defs\Object;
use Itafroma\Prim\Newstruc;

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
 * @param Newstruc $o The object to access.
 * @param mixed $p The property to retrieve.
 * @return mixed The property value.
 */
function oget(Newstruc $o, $p) {
    $v = ($o instanceof Object) ?  $o->oprops : $o->rprops;

    if (empty($v)) {
        return [];
    }

    return isset($v[$p]) ? $v[$p] : [];
}

/**
 * Sets an object property.
 *
 * @param Newstruc $o The object to modify.
 * @param mixed $p The property to modify.
 * @param mixed $x The value to set.
 */
function oput(Newstruc $o, $p, $x, $add = false) {
    if ($o instanceof Object) {
        $o->oprops[$p] = $x;
    }
    else {
        $o->rprops[$p] = $x;
    }

    return $o;
}

$rooms = [];
