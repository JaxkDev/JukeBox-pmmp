<?php

namespace Jackthehack21\JukeBox;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\block\BlockFactory;
use Jackthehack21\JukeBox\Block\JukeBox;

class Main extends PluginBase implements Listener
{

    private static $instance = null;

    public function onEnable()
    {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("JukeBox by Jackthehack21, Enabled !);

        registerThings()
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function registerThings(){
        BlockFactory::registerBlock(new JukeBox(84, name="JukeBox"));
    }

}