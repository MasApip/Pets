<?php

namespace ArasakaID\Pets\traits;

trait CanBeBabyTrait {

    private $baby = false;

    public function setBaby(bool $baby = true){
        $this->setGenericFlag(self::DATA_FLAG_BABY, $baby);
        if($baby){
            $this->setScale(0.5);
        } else {
            $this->setScale(1);
        }
        $this->baby = $baby;
    }

    public function isBaby(): bool
    {
        return $this->baby;
    }

}