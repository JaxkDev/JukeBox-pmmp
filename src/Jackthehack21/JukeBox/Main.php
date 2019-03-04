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

    private static $instance = null;

    public function onLoad(){
        $this->registerEverything();
    }

    public function onEnable()
    {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("JukeBox by Jackthehack21, Enabled !");
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    private function registerEverything(){
        BlockFactory::registerBlock(new JukeBox(84, "JukeBox"), true); //true to fix /reload
        //todo two blocks one for record in, one for out

        //Records here:
        ItemFactory::registerItem(new Record(500, "13", LevelSoundEventPacket::SOUND_RECORD_13), true);

        //Add to creative menu:
        Item::initCreativeItems();
    }

}