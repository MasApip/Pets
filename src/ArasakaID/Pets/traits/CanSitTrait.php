<?php

namespace ArasakaID\Pets\traits;

use pocketmine\Player;

trait CanSitTrait {

    private $sit = false;

    public function onInteract(Player $playerInteract){
        if($this->isOwner($playerInteract)){
            if($this->isSit()){
                $this->setSit(false);
            } else {
                $this->setSit();
            }
        }
    }

    public function setSit($sit = true){
        $this->sit = $sit;
        $this->setGenericFlag(self::DATA_FLAG_SITTING, $sit);
    }

    public function isSit(): bool
    {
        return $this->sit;
    }

}