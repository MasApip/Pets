<?php

namespace ArasakaID\Pets\pets;

use ArasakaID\Pets\traits\CanBeBabyTrait;
use pocketmine\entity\Creature;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;
use const pocketmine\RESOURCE_PATH;

abstract class BasePets extends Creature {
    use CanBeBabyTrait;

    private $speed = 0.5;

    /** @var Player $owner*/
    private $owner = null;

    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function attack(EntityDamageEvent $source): void
    {
        if($source instanceof EntityDamageByEntityEvent){
            $source->setCancelled();
        }
        parent::attack($source);
    }

    public function checkOwner(): bool
    {
        if($this->owner == null or !$this->owner->isOnline()){
            return false;
        }
        return true;
    }

    public function setOwner(Player $player){
        $this->owner = $player;
    }

    public function getOwner(): ?Player
    {
        return $this->owner;
    }

    public function isOwner(Player $player): bool
    {
        if($this->getOwner() === null){
            return false;
        }
        return $player->getId() === $this->owner->getId();
    }

    public function getEntity(): BasePets
    {
        return $this;
    }

}
