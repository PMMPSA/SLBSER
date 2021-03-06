<?php

namespace BANKRTDV\BossBarAPI;

use pocketmine\scheduler\PluginTask;
use pocketmine\plugin\Plugin;
use pocketmine\Player;

class SendTask extends PluginTask{

	public function __construct(Plugin $owner){
		parent::__construct($owner);
	}

	public function onRun($currentTick){
		$this->getOwner()->sendBossBar();
	}

	public function cancel(){
		$this->getHandler()->cancel();
	}
}
?>