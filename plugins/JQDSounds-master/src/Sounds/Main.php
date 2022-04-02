<?php

namespace Sounds;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\level\sound\NoteblockSound;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\Sound;
use pocketmine\level\sound\PopSound;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;

class Main extends PluginBase implements Listener {

	public function onEnable(){
		$this->getLogger()->info(C::GREEN." Activated!");
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
	}
	public function onDisable(){
		$this->getLogger()->info(C::RED." Deactivated");
	}
	public function onDeath(PlayerDeathEvent $event){
		$event->getPlayer()->getLevel()->addSound(new AnvilFallSound($event->getPlayer()));
	}
	public function onQuit(PlayerQuitEvent $event){
		$event->getPlayer()->getLevel()->addSound(new NoteblockSound($event->getPlayer()));
	}
	public function onJoin(PlayerJoinEvent $event){
		$event->getPlayer()->getLevel()->addSound(new NoteblockSound($event->getPlayer()));
	}
}
