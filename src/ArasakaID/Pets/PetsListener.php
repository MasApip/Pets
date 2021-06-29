<?php

namespace ArasakaID\Pets;

use ArasakaID\Pets\data\PetsData;
use ArasakaID\Pets\interfaces\CanSitInterface;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;

class PetsListener implements Listener{

    private $plugin;

    public function __construct(Pets $plugin)
    {
        $this->plugin = $plugin;
    }

    public function getPlayerJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if(PetsData::hasPet($player)){
            PetsData::respawnAllPet($player);
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        PetsData::saveCache();

        $playerPets = PetsData::getPet($player);
        if($playerPets !== null) {
            foreach ($playerPets as $playerPet) {
                if ($playerPet !== null && !$playerPet->isFlaggedForDespawn()) {
                    $playerPet->flagForDespawn();
                }
            }
        }
    }

    public function onInteractWithEntity(DataPacketReceiveEvent $event)
    {
        $pk = $event->getPacket();
        $player = $event->getPlayer();
        if ($pk instanceof InventoryTransactionPacket) {
            if ($pk->trData instanceof UseItemOnEntityTransactionData && $pk->trData->getActionType() === $pk->trData::ACTION_INTERACT) {
                $entity = $player->level->getEntity($pk->trData->getEntityRuntimeId());
                if ($entity instanceof CanSitInterface){
                    $entity->onInteract($player);
                }
            }
        }
    }

}
