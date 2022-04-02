<?php

namespace Hender;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\level\sound\GhastShootSound;

class Main extends PluginBase implements Listener {
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if(isset($args[0])){
			switch(strtolower($args[0])){
				case "1":
		 $item = $sender->getInventory()->getItemInHand();
      $enchantment = Enchantment::getEnchantment(mt_rand(0, 40))->setLevel((int)rand(1,7));;
      $xp = $sender->getXpLevel();
		if($xp < 5)
		{
			$sender->sendTitle("§l§e⚒§aM§6o§eN§dp§e⚒\n§eE§aX§eP §6ของคุณไม่พอ!");
		}else{
			$sender->takeXpLevel(5);
			$item->addEnchantment($enchantment);
            $sender->getInventory()->setItemInHand($item);
			$sender->sendTitle("§l§e⚒§aM§6o§eN§dp§e⚒\n§aทำการสุ่มสำเร็จ\n§c-§e5 §aE§eX§aP");
			$sender->getLevel()->addSound(new GhastShootSound($sender));
		}
		  break;
	  }
    }
  }
}