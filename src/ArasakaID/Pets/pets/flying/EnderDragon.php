<?php

namespace ArasakaID\Pets\pets\flying;

use ArasakaID\Pets\pets\FlyingPets;

class EnderDragon extends FlyingPets{

    public $width = 13;
    public $height = 4;

    const NETWORK_ID = self::ENDER_DRAGON;

    public function getName(): string{
        return "EnderDragon";
    }

}