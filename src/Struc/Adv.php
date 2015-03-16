<?php

/**
 * @file
 * Adventurer structure definition.
 *
 * @see Itafroma\Zork\Struc\StrucInterface
 * @see Itafroma\Zork\newstruc()
 */

namespace Itafroma\Zork\Struc;

class Adv implements StrucInterface
{
    /** @var ROOM $room Where the adventurer is */
    public $aroom;

    /** @var <LIST [REST OBJECT]> $aobjs What the adventurer is carrying */
    public $aobjs;

    /** @var Itafroma\Zork\Struc\Object|boolean $avehicle What the adventurer is riding in */
    public $avehicle = false;

    /** @var Itafroma\Zork\Struc\Object $aobj What the adventurer is */
    public $aobj;

    /** @var RAPPLIC $aaction Special action for robot, etc. */
    public $aaction;

    /** @var int $astrength Fighting strength */
    public $astrength;

    /**
     * @var int $aflags Flags
     *
     * This must be the same offset as \Itafroma\Zork\Struc\Object::$oflags.
     */
    public $aflags;
}
