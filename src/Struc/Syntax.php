<?php

/**
 * @file
 * Syntax structure definition.
 *
 * @see Itafroma\Zork\Struc\StrucInterface
 * @see Itafroma\Zork\newstruc()
 */

namespace Itafroma\Zork\Struc;

class Syntax implements StrucInterface
{
    /** @var VARG $syn1 Direct object, more or less */
    public $syn1;

    /** @var VARG $syn2 Indirect object, more or less */
    public $syn2;

    /** @var VERB $sfcn Function to handle this action */
    public $sfcn;

    /** @var int $sflags Flag bits for this verb */
    public $sflags;
}
