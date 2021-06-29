<?php

namespace ArasakaID\Pets\data;

use ArasakaID\Pets\Pets;
use ArasakaID\Pets\pets\BasePets;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\utils\Config;

class PetsData {

    private static $playerPets = [];

    private static $cache;

    public function __construct(Pets $pets)
    {
        self::$cache = new Config($pets->getDataFolder() . "pets.json", Config::JSON);
        self::loadCache();
    }

    public static function loadCache(){
        self::$playerPets = self::$cache->getAll();
        var_dump(self::$playerPets);
    }

    public static function saveCache(){
        self::$cache->setAll(PetsData::$playerPets);
        self::$cache->save();
    }

    public static function setPlayerPets(Player $player, BasePets $pets)
    {
        $petId = 1;
        if (isset(PetsData::$playerPets[$player->getName()]))
            foreach (PetsData::$playerPets[$player->getName()] as $petIds => $pet) {
                if (is_int($petIds)) $petId++;
            }
        PetsData::$playerPets[$player->getName()][$petId]["type"] = $pets->getName();
        PetsData::$playerPets[$player->getName()][$petId]["lastId"] = $pets->getId();
        PetsData::$playerPets[$player->getName()][$petId]["baby"] = $pets->isBaby();
        PetsData::$playerPets[$player->getName()][$petId]["nametag"] = $pets->getNameTag();
    }

    public static function respawnAllPet(Player $player)
    {
        foreach (PetsData::$playerPets[$player->getName()] as $petId => $pet) {
            $data = PetsData::$playerPets[$player->getName()][$petId];
            $pet = Entity::createEntity($data["type"], $player->getLevelNonNull(), Entity::createBaseNBT($player->asVector3()));
            if ($pet instanceof BasePets) {
                $pet->setOwner($player);
                $pet->setBaby($data["baby"]);

                $pet->setNameTagVisible();
                $pet->setNameTagAlwaysVisible();
                $pet->setNameTag($data["nametag"]);

                $pet->spawnToAll();

                PetsData::$playerPets[$player->getName()][$petId]["lastId"] = $pet->getId();
            }
        }
    }

    public static function hasPet(Player $player): bool
    {
        if(isset(PetsData::$playerPets[$player->getName()])){
            return true;
        }
        return false;
    }

    /**
     * @param Player $player
     * @return BasePets[]
     */
    public static function getPet(Player $player): ?array
    {
        if(!isset(PetsData::$playerPets[$player->getName()])){
            return null;
        }
        $pets = null;
        foreach (PetsData::$playerPets[$player->getName()] as $petId => $petData) {
            $id = PetsData::$playerPets[$player->getName()][$petId]["lastId"];
            $pet = $player->level->getEntity($id);
            if ($pet instanceof BasePets) {
                $pets[] = $pet;
            }
        }
        return $pets;
    }

    public static function resetAllPet(Player $player){
        if(isset(PetsData::$playerPets[$player->getName()])){
            foreach (PetsData::$playerPets[$player->getName()] as $petId => $petData) {
                $id = PetsData::$playerPets[$player->getName()][$petId]["lastId"];
                $pet = $player->level->getEntity($id);
                if ($pet instanceof BasePets && !$pet->isFlaggedForDespawn()) {
                    $pet->flagForDespawn();
                }
            }

            self::$cache->remove($player->getName());
            unset(PetsData::$playerPets[$player->getName()]);
        }
    }

}