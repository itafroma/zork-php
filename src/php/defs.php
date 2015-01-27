<?php

/**
 * @file
 * Defines.
 */

use Itafroma\Defs\Object;
use Itafroma\Prim\Newstruc;

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
