<?php

/*
 * BossBarAPI
 */
namespace BANKRTDV\BossBarAPI;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\network\protocol\SetEntityDataPacket;

class Main extends PluginBase implements Listener{
	private static $instance = null;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getNetwork()->registerPacket(BossEventPacket::NETWORK_ID, BossEventPacket::class);
	}

	public static function getInstance(){
		return self::$instance;
	}

	public function onLoad(){
		self::$instance = $this;
	}
}