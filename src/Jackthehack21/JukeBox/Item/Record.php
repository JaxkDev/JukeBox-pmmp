<?php

namespace Jackthehack21\JukeBox\Item;

use pocketmine\item\Item;

class Record extends Item {

    private $soundId;

	public function __construct(int $id, string $Uname, int $soundId){
        parent::__construct($id, 0, "Music-Disk ".$Uname);
        $this->soundId = $soundId;
    }
    
	public function getMaxStackSize(): int{
		return 1;
    }

    public function getUniqueId(): int{
        return $this->getId();
    }

    public function getSoundId(): int{
        return $this->soundId;
    }

}