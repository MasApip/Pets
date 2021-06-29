<?php

namespace ArasakaID\Pets\command;

use ArasakaID\Pets\data\PetsData;
use ArasakaID\Pets\pets\BasePets;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PetsCommand extends Command{

    public function __construct()
    {
        parent::__construct("pets", "Pets main command");
        $this->setPermission("pets.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player && isset($args[0])) {
            switch ($args[0]) {
                case "spawn":
                    if(isset($args[1])) {
                        $pet = Entity::createEntity($args[1], $sender->getLevelNonNull(), Entity::createBaseNBT($sender->asVector3()));
                        if($pet instanceof BasePets){
                            $pet->setOwner($sender);
                            if(isset($args[2]) && $args[2] == "true"){
                                $pet->setBaby(true);
                            }
                            if(isset($args[3])){
                                $pet->setNameTagVisible();
                                $pet->setNameTagAlwaysVisible();
                                $pet->setNameTag($args[3]);
                            }
                            $pet->spawnToAll();

                            PetsData::setPlayerPets($sender, $pet);
                        } else {
                            $sender->sendMessage(TextFormat::RED . "Pets with name " . $args[1] . " is not available!");
                        }
                    } else {
                        $sender->sendMessage(TextFormat::RED . "Usage: /pets spawn <entityName> <isBaby> <petName>");
                    }
                    break;
                case "clear":
                    if(PetsData::hasPet($sender)){
                        PetsData::resetAllPet($sender);
                    }
                    break;
                default:
                    break;
            }
        }
    }
}