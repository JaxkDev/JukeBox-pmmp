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

namespace Jackthehack21\JukeBox\Event;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\block\Block;

use Jackthehack21\JukeBox\Main;

class RecordStopEvent extends JukeboxEvent{
    
    private $plugin;
    private $player;
    private $block;
    private $record;

	public function __construct(Main $plugin, Block $block, Item $record, Player $player = null){
        $this->block = $block;
        $this->player = $player;
        $this->record = $record;
		parent::__construct($plugin);
    }
    
	public function getPlayer(){
		return $this->player;
    }
    
    public function getBlock(){
        return $this->block;
    }

    public function getRecord(){
        return $this->record;
    }
}