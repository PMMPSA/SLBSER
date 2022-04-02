<?php

namespace bau\task;

use pocketmine\scheduler\PluginTask;
use bau\Main;

class Close extends PluginTask{
	
	private $plugin;
	
	public function __construct(Main $plugin, $player, $type){
		$this->plugin = $plugin;
		$this->type = $type;
		$this->player = $player;
		parent::__construct($plugin);
	}
	
	public function unset($type){
		$name = $this->player->getName();
		switch($type){
			case "all":
			if(isset($this->all[$name])){
				if($this->all[$name] == "off"){
					unset($this->all[$name]);
					$this->all[$name] = "on";
				} else {
					unset($this->all[$name]);
				}
			}
			break;
			case "inv":
			unset($this->plugin->inv[$name]);
			break;
			case "icone":
			unset($this->plugin->icone[$name]);
			break;
			case "aum":
			unset($this->plugin->aum[$name]);
			break;
		}
	}
	
	public function onRun($timer){
		$this->unset($this->type);
		$this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
	}
}