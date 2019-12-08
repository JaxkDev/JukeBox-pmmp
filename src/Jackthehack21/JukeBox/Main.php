<?php

/*
*   JukeBox Pocketmine Plugin
*   Copyright (C) 2019 JaxkDev (Jack Honour)
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

namespace Jackthehack21\JukeBox;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\utils\Config;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\block\BlockFactory;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;

use Jackthehack21\JukeBox\Tile\JBTile;
use Jackthehack21\JukeBox\Item\Record;
use Jackthehack21\JukeBox\Block\JukeBox;

class Main extends PluginBase
{

    private static $instance = null;

    public function onLoad(){
        $this->registerItems();
        $this->registerConfig();
    }

    public function onEnable()
    {
        self::$instance = $this;
    }

    private function registerConfig(){
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML, []);
    }

    private function registerItems(){
        BlockFactory::registerBlock(new JukeBox(84, "JukeBox", $this), false); //set to false as i do not support 'reloads'

	
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
        
        Tile::registerTile(JBTile::class, ["Jukebox"]);

        //Add to creative menu:
        Item::initCreativeItems();
    }

    public static function getInstance()
    {
        return self::$instance;
    }
	
	public function sendForm(Player $player){
		if($this->cfg->get("ingame_notice")){
			$this->debug("Sending Form to ".$player->getName());
			
			$data = [];
			$data["type"] = "form";
			$data["title"] = "JukeBox - NOTICE";
			$data["content"] = "Notice: The sound will not be heard, unless the indivudual player has downloaded the 'MUSIC' dlc from the mcpe store in menu.\n\nTo remove this notice go to config.yml in plugin config for JukeBox.";
            $data["buttons"] = [];
            
			$pk = new ModalFormRequestPacket();
			$pk->formId = 1; //ID is not important here does not collide with pmmp ID system and we dont hndle response.
			$pk->formData = json_encode($data);
			$player->dataPacket($pk);
		}
	}

    public function debug(string $msg){
        if($this->cfg->get('debug')){
            $this->getLogger()->info("[DEBUG] : ".$msg);
        }
    }

}
