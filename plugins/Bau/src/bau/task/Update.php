<?php

namespace bau\task;

use pocketmine\scheduler\PluginTask;
use bau\Main;

class Update extends PluginTask{
	
	private $plugin;
	
	public function __construct(Main $plugin, $player, $inv){
		$this->plugin = $plugin;
		$this->inv = $inv;
		$this->player = $player;
		parent::__construct($plugin);
	}
	
	public function getInventory(){
		return $this->inv;
	}
	
	public function onRun($timer){
		$inv = $this->getInventory();
		$name = $this->player->getName();
		if(isset($this->plugin->open[$name])){
			$this->plugin->saveItens($inv->getContents(), $name, $this->plugin->bau[$name]);
		} else {
			unset($this->plugin->chest[$name]);
			unset($this->plugin->bau[$name]);
			$this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}