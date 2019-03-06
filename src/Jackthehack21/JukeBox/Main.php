<?php

namespace Jackthehack21\JukeBox;

use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\block\BlockFactory;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use Jackthehack21\JukeBox\Item\Record;
use Jackthehack21\JukeBox\Block\JukeBox;

class Main extends PluginBase implements Listener
{

    public function onLoad(){
        $this->registerEverything();
    }

    public function onEnable()
    {
        // save/get config
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("JukeBox by Jackthehack21, Enabled !");
    }

    private function registerEverything(){
        BlockFactory::registerBlock(new JukeBox(84, "JukeBox", $this), true); //true to fix /reload

        //Records here:
        ItemFactory::registerItem(new Record(500, "13", LevelSoundEventPacket::SOUND_RECORD_13), true);
		ItemFactory::registerItem(new Record(501, "Cat", LevelSoundEventPacket::SOUND_RECORD_CAT), true);
		ItemFactory::registerItem(new Record(502, "Blocks", LevelSoundEventPacket::SOUND_RECORD_BLOCKS), true);
		ItemFactory::registerItem(new Record(503, "Chirp", LevelSoundEventPacket::SOUND_RECORD_CHIRP), true);
		ItemFactory::registerItem(new Record(504, "Far", LevelSoundEventPacket::SOUND_RECORD_FAR), true);
		ItemFactory::registerItem(new Record(505, "Mall", LevelSoundEventPacket::SOUND_RECORD_MALL), true);
		ItemFactory::registerItem(new Record(506, "Mellohi", LevelSoundEventPacket::SOUND_RECORD_MELLOHI), true);
		ItemFactory::registerItem(new Record(507, "Stal", LevelSoundEventPacket::SOUND_RECORD_STAL), true);
		ItemFactory::registerItem(new Record(508, "Strad", LevelSoundEventPacket::SOUND_RECORD_STRAD), true);
		ItemFactory::registerItem(new Record(509, "Ward", LevelSoundEventPacket::SOUND_RECORD_WARD), true);
		ItemFactory::registerItem(new Record(510, "11", LevelSoundEventPacket::SOUND_RECORD_11), true);
		ItemFactory::registerItem(new Record(511, "Wait", LevelSoundEventPacket::SOUND_RECORD_WAIT), true);

        //Add to creative menu:
        Item::initCreativeItems();
    }

    public function debug(string $msg){
        $this->getLogger()->info("[DEBUG] : ".$msg);
    }

}