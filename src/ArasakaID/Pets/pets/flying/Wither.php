<?php

namespace ArasakaID\Pets\pets\flying;

use ArasakaID\Pets\pets\FlyingPets;

class Wither extends FlyingPets{

    public $width = 1;
    public $height = 3;

    const NETWORK_ID = self::WITHER;

    public function getName(): string
    {
        return "Wither";
    }
}