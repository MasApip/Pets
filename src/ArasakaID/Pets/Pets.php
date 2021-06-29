<?php

namespace ArasakaID\Pets;

use ArasakaID\Pets\command\PetsCommand;
use ArasakaID\Pets\data\PetsData;
use ArasakaID\Pets\pets\flying\EnderDragon;
use ArasakaID\Pets\pets\flying\Wither;
use ArasakaID\Pets\pets\walking\Wolf;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;

class Pets extends PluginBase{

    public function onEnable()
    {
        $this->registerPets();

        $this->getServer()->getCommandMap()->register($this->getName(), new PetsCommand());

        $this->getServer()->getPluginManager()->registerEvents(new PetsListener($this), $this);

        new PetsData($this);
    }

    public function onDisable()
    {
        PetsData::saveCache();
    }

    private function registerPets(){
        $petsClass = [
            Wolf::class,

            Wither::class, EnderDragon::class
        ];

        foreach ($petsClass as $pets){
            Entity::registerEntity($pets, true);
        }
    }

}
