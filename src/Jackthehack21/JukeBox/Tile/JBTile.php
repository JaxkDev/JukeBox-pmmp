<?php

namespace Jackthehack21\JukeBox\Tile;

use Jackthehack21\JukeBox\Item\Record;

use pocketmine\player;
use pocketmine\item\Item;
use pocketmine\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

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
	}

	public function handleBreak(Item $item, Player $player){
		$this->getBlock()->debug("Handling Break.");
		if($this->has_record){
			$this->getBlock()->debug("Dropping record");
			$this->updateRecord();
		}
	}

	public function updateRecord(Item $record = null, Player $player = null){
		if($record == null){
			$this->dropRecord();
		} else {
			$this->getBlock()->debug("Adding record");
			$this->record = $record;
			$this->has_record = true;

			$this->getLevel()->broadcastLevelSoundEvent($this, $record->getSoundId());
		
			$pk = new TextPacket();
			$pk->type = TextPacket::TYPE_JUKEBOX_POPUP;
			$pk->message = "Now Playing: C418 - ".$record->getSoundName();
			$player->dataPacket($pk);
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

}
