<?php

namespace ArasakaID\Pets\pets;

use ArasakaID\Pets\interfaces\CanSitInterface;
use pocketmine\math\Vector3;

abstract class WalkingPets extends BasePets{

    public $randomMoveDelay = 0;
    /** @var Vector3 $targetMovePosition */
    public $targetMovePosition = null;

    public function onUpdate(int $currentTick): bool
    {
        $this->handlePetsJump();
        $this->handlePetsMovement();

        return parent::onUpdate($currentTick);
    }

    public function handlePetsMovement(){
        if($this->checkOwner()){
            $owner = $this->getOwner();
            $ownerPos = $owner->asVector3();
            if($this->distance($ownerPos) <= 5){
                if($this instanceof CanSitInterface && $this->isSit()){
                    $this->lookAt($ownerPos);
                    return;
                }
                if($this->distance($ownerPos) >= 100 or $this->level->getName() !== $owner->level->getName()){
                    $this->teleport($owner->getLocation());
                    return;
                }
                if ($this->randomMoveDelay >= 0) {
                    if($this->randomMoveDelay == 0) {
                        $target = $this->findRandomPosition();
                        $block = $this->getLevel()->getBlock($target);
                        if ($block->getId() !== 0) {
                            $this->targetMovePosition = $target->add(0, 1);
                            $this->randomMoveDelay = mt_rand(30, 70);
                        }
                    }
                    if($this->targetMovePosition !== null){
                        $this->moveTo($this->targetMovePosition, 0, 0, 0);
                    }
                    $this->randomMoveDelay--;
                }
            } else {
                $this->targetMovePosition = null;
                $this->moveTo($ownerPos);
            }
        }
    }

    public function moveTo(Vector3 $pos, float $xOffset = 0.0, float $yOffset = 0.0, float $zOffset = 0.0): void
    {
        $x = $pos->x + $xOffset - $this->x;
        $y = $pos->y + $yOffset - $this->y;
        $z = $pos->z + $zOffset - $this->z;
        $xz = $x * $x + $z * $z;
        $xz_f = sqrt($xz);

        if ($xz < mt_rand(3, 8)) {
            $this->motion->x = 0;
            $this->motion->z = 0;
        } else {
            $speed = $this->getSpeed() * 0.17;
            $this->motion->x = $speed * ($x / $xz_f);
            $this->motion->z = $speed * ($z / $xz_f);
        }

        $this->yaw = rad2deg(atan2(-$x, $z));
        $this->pitch = rad2deg(-atan2($y, $xz_f));

        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        $this->updateMovement();
    }

    public function findRandomPosition(): Vector3
    {
        $x = mt_rand(-10, 10) + $this->x;
        $z = mt_rand(-10, 10) + $this->z;
        return new Vector3($x, $this->getLevelNonNull()->getHighestBlockAt($x, $z), $z);
    }

    public function handlePetsJump(){
        if($this->isUnderwater()){
            $this->motion->y = 0.1;
        }
        if($this->isCollidedHorizontally){
            $this->jump();
        }
    }

}