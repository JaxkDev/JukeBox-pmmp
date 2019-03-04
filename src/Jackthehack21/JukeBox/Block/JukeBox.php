<?php

declare(strict_types=1);

namespace Jackthehack21\JukeBox\Block;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\block\Solid;
use pocketmine\block\BlockToolType;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use Jackthehack21\JukeBox\Item\Record;

class JukeBox extends Solid{

    public $record_inside = false;
    public $record = null; //null or Record

    public function __construct(int $id,string $name = null)
    {
        parent::__construct($id, 0, $name, null);
    }

    public function getFlammability() : int{
        return 2;
    }

    public function getHardness() : float{
		return 2.0;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_AXE;
    }

    public function onActivate(Item $item, Player $player = null) : bool{
        printf("Activated\n");
        //Play / Stop sound.
        if(!$player instanceof Player){
            return false;
        }
        if($this->record_inside){
            $this->dropRecord();
        } else {
            if($item instanceof Record){
                $this->addRecord($item);
                $player->getInventory()->removeItem($item);
            }
        }
        return true;
    }
    
    public function onBreak(Item $item, Player $player = null) : bool{
        $this->dropRecord();
        return parent::onBreak($item, $player);
    }

    public function addRecord(Item $item) : void{
        printf("Adding record\n");
        $this->record_inside = true;
        $this->record = $item;
        $this->playSound($item->getSoundId());
    }

    public function dropRecord() : void{
        if($this->record_inside){
            printf("Dropping record\n");
            $this->record_inside = false;
            $this->getLevel()->dropItem($this->asVector3(), $this->record);
            $this->record = null;
            $this->stopSound();
        }
    }

    public function playSound(int $id) : void{
        printf("%s\n",$id);
        printf("Playing sound\n");
        $this->getLevel()->broadcastLevelSoundEvent($this, $id);
    }

    public function stopSound() : void{
        printf("Stopping sound\n");
        $this->getLevel()->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_STOP_RECORD);
    }
}