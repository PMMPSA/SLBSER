<?php

namespace bau\task;

use pocketmine\scheduler\PluginTask;
use bau\task\Update;
use bau\Main;

class Delayed extends PluginTask{
	
	private $plugin;
	
	public function __construct(Main $plugin, $player, $inv, $opt = true){
		$this->plugin = $plugin;
		$this->inv = $inv;
		$this->player = $player;
		$this->opt = $opt;
		parent::__construct($plugin);
	}
	
	public function getInventory(){
		return $this->inv;
	}
	
	public function onRun($timer){
		$inv = $this->getInventory();
		$this->player->addWindow($inv);
		if($this->opt == "mudar"){
			$this->plugin->icone[$this->player->getName()] = $inv;
		}
		if($this->opt == "aumentar"){
			$this->plugin->aum[$this->player->getName()] = $inv;
		}
		if($this->opt == "chest"){
			$this->plugin->open[$this->player->getName()] = true;
			$this->plugin->chest[$this->player->getName()] = $this->inv;
			$this->getOwner()->getServer()->getScheduler()->scheduleRepeatingTask(new Update($this->plugin, $this->player, $inv), 1);
		}
		$this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
	}
}