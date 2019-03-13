<?php

/*
*   JukeBox Pocketmine Plugin
*   Copyright (C) 2019 Jackthehack21 (Jack Honour)
*
*   This program is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   any later version.
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

namespace Jackthehack21\JukeBox\Tile;

use Jackthehack21\JukeBox\Item\Record;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\level\particle\GenericParticle;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use Jackthehack21\JukeBox\Main;
use Jackthehack21\JukeBox\Event\RecordPlayEvent;
use Jackthehack21\JukeBox\Event\RecordStopEvent;

use mt_rand;
use mt_getrandmax;

class JBTile extends Spawnable{

	public $has_record = false;
	public $record = null;

	public function getDefaultName() : string{
		return "Jukebox";
	}

	public function handleInteract(Item $item, Player $player = null){
		$this->getBlock()->debug("Handling Interact.");
		if($this->has_record){
            $this->updateRecord();
        } else {
            if($item instanceof Record){
                $this->updateRecord($item, $player);
                $player->getInventory()->removeItem($item);
            }
		}
		$this->scheduleUpdate();
	}

	public function handleBreak(Item $item, Player $player){
		$this->getBlock()->debug("Handling Break.");
		if($this->has_record){
			$this->updateRecord();
		}
	}

	public function updateRecord(Item $record = null, Player $player = null){
		if($record == null){

			//RecordStopEvent

			$this->getBlock()->debug("Dropping record");

			$ev = new RecordStopEvent(Main::getInstance(), $this->getBlock(), $this->record, $player);
			Server::getInstance()->getPluginManager()->callEvent($ev);
			if($ev->isCancelled()){
				$this->getBlock()->debug("Event Cancelled.");
				return;
			} else {
				$this->getBlock()->debug("Event not Cancelled.");
			}

			$this->dropRecord();
		} else {

			//RecordPlayingEvent

			$this->getBlock()->debug("Adding record");

			$ev = new RecordPlayEvent(Main::getInstance(), $this->getBlock(), $record, $player);
			Server::getInstance()->getPluginManager()->callEvent($ev);
			if($ev->isCancelled()){
				$this->getBlock()->debug("Event Cancelled.");
				return;
			} else {
				$this->getBlock()->debug("Event not Cancelled.");
			}

			$this->record = $record;
			$this->has_record = true;

			$this->getLevel()->broadcastLevelSoundEvent($this, $record->getSoundId());

			$plug = Main::getInstance();
			if($plug->cfg->get("popup") === true){
				$msg = str_replace("{NAME}",$record->getSoundName(),$plug->cfg->get("popup_text"));
				$pk = new TextPacket();
				$pk->type = TextPacket::TYPE_JUKEBOX_POPUP;
				$pk->message = $msg;
				$player->dataPacket($pk);
			}
		}
		$this->onChanged();
	}

	public function dropRecord(){
		if($this->has_record){
			$this->getBlock()->debug("Dropping record.");
			$this->getLevel()->dropItem($this->asVector3(), $this->record);
            $this->has_record = false;
            $this->record = null;
            $this->stopSound();
        }
	}

	public function stopSound() : void{
        $this->getBlock()->debug("Stopping sound.");
        $this->getLevel()->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_STOP_RECORD);
	}
	
	public function onUpdate() : bool{
		//$this->getBlock()->debug("Update");
		$plug = Main::getInstance();
		if($this->has_record && $plug->cfg->get("particles") === true){
			if(Server::getInstance()->getTick() % $plug->cfg->get("particles_ticks") == 0 ){ //todo configurable
				//$this->getBlock()->debug("Adding particle"); even for debug thats quite a bit :)
				$this->level->addParticle(new GenericParticle($this->add($this->randomFloat(0.3,0.7), $this->randomFloat(1.2,1.6), $this->randomFloat(0.3,0.7)), 36));
			}
		}
		return true;
	}

	////// BELOW IS STATE SAVING DONT TOUCH ///////

	public function readSaveData(CompoundTag $nbt) : void{
		if($nbt->hasTag("Record")){
			$this->record = Item::nbtDeserialize($nbt->getCompoundTag("Record"));
		}
	}
	protected function writeSaveData(CompoundTag $nbt) : void{
		if($this->record !== null){
			$nbt->setTag($this->record->nbtSerialize(-1, "Record"));
		}
	}
	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{} //must stay

	private function randomFloat($min = 0, $max = 1) {
		return $min + mt_rand() / mt_getrandmax() * ($max - $min);
	}

}
