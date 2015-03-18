<?php

/**
 * @file
 * Conditional exit structure definition.
 *
 * @see Itafroma\Zork\Struc\StrucInterface
 * @see Itafroma\Zork\newstruc()
 */

namespace Itafroma\Zork\Struc;

class Cexit implements StrucInterface
{
    /** @var mixed $cxflag Condition flag */
    public $cxflag;

    /** @var mixed $cxroom Room it protects */
    public $cxroom;

    /** @var string|false $cxstr Description */
    public $cxstr = false;

    /** @var RAPPLIC $cxaction Exit function */
    public $cxaction;
}
