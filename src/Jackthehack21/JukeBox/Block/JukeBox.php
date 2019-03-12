<?php

/*
*   JukeBox Pocketmine Plugin
*   Copyright (C) 2019 Jackthehack21 (Jack Honour)
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

declare(strict_types=1);

namespace Jackthehack21\JukeBox\Block;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\block\Block;
use pocketmine\block\Solid;
use pocketmine\math\Vector3;
use pocketmine\block\BlockToolType;

use Jackthehack21\JukeBox\Tile\JBTile;
use Jackthehack21\JukeBox\Item\Record;
use Jackthehack21\JukeBox\Main;

class JukeBox extends Solid{

    private $plugin;

    public function __construct(int $id,string $name = null, Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($id, 0, $name, null);
    }

    public function debug(string $msg) : void{
        $this->plugin->debug($msg);
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
	
	public function verifyTile(Item $item, Player $player) : int{
		if($this->getLevel()->getTile($this) === null){
			Tile::createTile("Jukebox", $this->getLevel(), JBTile::createNBT($this, 0, $item, $player));
			$this->debug("Tile not found, created new tile.");
			return 1;
		}
		$this->debug("Tile Verified");
		return 0;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		$this->getLevel()->setBlock($this, $this, true, true);
		$this->debug("New Jukebox placed, Creating Tile.");
		$this->verifyTile($item, $player);
		if($this->getLevel()->getTile($this) === null){
			$this->getLogger()->error("Failed to create tile.");
		}
        return true;
	}

    public function onActivate(Item $item, Player $player = null) : bool{
        $this->debug("Activated");
        if(!$player instanceof Player){
			$this->debug("Not activated by Player.");
            return false;
        }
		$this->verifyTile($item, $player);
        $JBTile = $this->getLevel()->getTile($this);
        $JBTile->handleInteract($item, $player);
        return true;
    }
    
    public function onBreak(Item $item, Player $player = null) : bool{
        $this->debug("Broke JukeBox");
		$this->verifyTile($item, $player);
        $JBTile = $this->getLevel()->getTile($this);
        $JBTile->handleBreak($item, $player);
        return parent::onBreak($item, $player);
    }
}
