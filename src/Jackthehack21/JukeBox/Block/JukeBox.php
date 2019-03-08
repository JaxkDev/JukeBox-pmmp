<?php

declare(strict_types=1);

namespace Jackthehack21\JukeBox\Block;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\block\Solid;
use pocketmine\block\BlockToolType;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use Jackthehack21\JukeBox\Item\Record;
use Jackthehack21\JukeBox\Main;

class JukeBox extends Solid{

    public $record_inside = false;
    public $record = null; //null or Record

    private $plugin; //todo remove

    public function __construct(int $id,string $name = null, Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($id, 0, $name, null);
    }

    public function debug(string $msg) : void{
        $this->plugin->debug($msg);
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
        $this->debug("Activated");
        //Play / Stop sound.
        if(!$player instanceof Player){
			$this->debug("Not activated by Player.");
            return false;
        }
        if($this->record_inside){
            $this->dropRecord();
        } else {
            if($item instanceof Record){
                $this->addRecord($item, $player);
                $player->getInventory()->removeItem($item);
            } else {
				$this->debug("Not activated by Record.");
			}
        }
		$this->level->setBlock($this, $this); //Part 1 or testing.
        return true;
    }
    
    public function onBreak(Item $item, Player $player = null) : bool{
        $this->dropRecord();
        return parent::onBreak($item, $player);
    }

    public function addRecord(Item $item, Player $player) : void{
        $this->debug("Adding record");
        $this->record_inside = true;
        $this->record = $item;
        $this->playSound($item->getSoundId(), $player);
    }

    public function dropRecord() : void{
        if($this->record_inside){
            $this->debug("Dropping record.");
            $this->record_inside = false;
            $this->getLevel()->dropItem($this->asVector3(), $this->record);
            $this->record = null;
            $this->stopSound();
        }
    }

    public function playSound(int $id, Player $player) : void{
        $this->debug("Playing sound: ".$id);
		
        $this->getLevel()->broadcastLevelSoundEvent($this, $id);
		
        $pk = new TextPacket();
		$pk->type = TextPacket::TYPE_JUKEBOX_POPUP;
        $pk->message = "Now Playing: X";
		$player->dataPacket($pk);
    }

    public function stopSound() : void{
        $this->debug("Stopping sound.");
        $this->getLevel()->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_STOP_RECORD);
    }
}
