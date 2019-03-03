<?php

declare(strict_types=1);

namespace Jackthehack21\JukeBox\Block;

use pocketmine\block\Solid;
use pocketmine\block\BlockToolType;

class JukeBox extends Solid{

	//public record_inside = false;

    public function __construct(int $id,string $name = null)
    {
        parent::__construct($id, 0, $name, null);
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

    public function onIncinerate() : void{
        //todo drop record if has one.
    }
}