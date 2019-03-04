<?php

namespace Jackthehack21\JukeBox\Item;

use pocketmine\item\Item;

class Record extends Item {

	public function __construct(int $id, string $name){
		parent::__construct($id, 0, $name);
    }
    
	public function getMaxStackSize(): int{
		return 1;
    }
    
}