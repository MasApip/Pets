<?php

namespace ArasakaID\Pets\interfaces;

use pocketmine\Player;

interface CanSitInterface {

    public function onInteract(Player $playerInteract);

    public function setSit($sit = true);

    public function isSit(): bool;

}