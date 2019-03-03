<?php

declare(strict_types=1);

namespace Jackthehack21\JukeBox\Block;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;

class JukeBox extends Block{

	//public record_inside = false;

    public function getFlammability() : int{
        return 2;
    }

    public function getHardness() : float{
		return 1;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_AXE;
	}

    public function onIncinerate() : void{
        //todo drop record if has one.
    }
}