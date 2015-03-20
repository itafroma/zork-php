<?php

/**
 * @file
 * Action structure definition.
 *
 * @see Itafroma\Zork\Struc\StrucInterface
 * @see Itafroma\Zork\newstruc()
 */

namespace Itafroma\Zork\Struc;

class Action implements StrucInterface
{
    /** @var PSTRING $vname Atom associated with this action */
    public $vname;

    /** @var VPSEC $vdecl Syntaxes for this verb (any number) */
    public $vdecl;

    /** @var string $vstr String to print when talking about this verb */
    public $vstr;
}
