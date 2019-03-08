<?php

namespace Jackthehack21\JukeBox\Tile;

use Jackthehack21\JukeBox\Item\Record;
use pocketmine\tile\Spawnable;

class JBTile extends Spawnable{

	public function getDefaultName() : string{
		return "JukeBox";
	}
	
	//Todo: nbt Tags to save data, should hopefully fix #2

}
