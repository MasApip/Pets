<?php

namespace ArasakaID\Pets\pets\walking;

use ArasakaID\Pets\interfaces\CanSitInterface;
use ArasakaID\Pets\pets\WalkingPets;
use ArasakaID\Pets\traits\CanSitTrait;

class Wolf extends WalkingPets implements CanSitInterface {
    use CanSitTrait;

    public const NETWORK_ID = self::WOLF;

    public $width = 0.6;
    public $height = 0.85;

    public function getName(): string
    {
        return "Wolf";
    }

}