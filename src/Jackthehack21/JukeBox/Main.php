<?php

namespace Jackthehack21\JukeBox;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener
{

    private static $instance = null;

    public function onEnable()
    {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("JukeBox by Jackthehack21, Enabled !);
    }

    public static function getInstance()
    {
        return self::$instance;
    }

}