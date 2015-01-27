<?php

/**
 * @file
 * <NEWSTRUC> replacement interface.
 *
 * <NEWSTRUC> is essentially an emulation of C's struct, something PHP does
 * via a class with public properties. This interface enables hinting for
 * functions that expect <NEWSTRUC>-derived ATOMs.
 *
 * @see Itafroma\Zork\newstruc()
 */

namespace Itafroma\Zork\Prim;

interface Newstruc
{
}
