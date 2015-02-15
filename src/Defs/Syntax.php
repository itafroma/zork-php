<?php

/**
 * @file
 * Syntax structure definition.
 *
 * @see Itafroma\Zork\Prim\Struc
 * @see Itafroma\Zork\newstruc()
 */

namespace Itafroma\Zork\Defs;

use Itafroma\Zork\Prim\Struc;

class Syntax implements Struc
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
