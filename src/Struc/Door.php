<?php

/**
 * @file
 * Door structure definition.
 *
 * @see Itafroma\Zork\Struc\StrucInterface
 * @see Itafroma\Zork\newstruc()
 */

namespace Itafroma\Zork\Struc;

class Door implements StrucInterface
{
    /** @var Itafroma\Zork\Struc\Object $dobj The door */
    public $dobj;

    /** @var Itafroma\Zork\Struc\Room $droom1 One of the rooms */
    public $droom1;

    /** @var Itafroma\Zork\Struc\Room $droom2 The other one */
    public $droom2;

    /** @var string|false $dstr What to print if closed */
    public $dstr = false;

    /** @var RAPPLIC $rapplic What to call to decide */
    public $daction;
}
