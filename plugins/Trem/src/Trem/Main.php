<?php

namespace Trem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\command\CommandReader;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\sound\GhastShootSound;
class Main extends PluginBase implements Listener{
	public $tag = "§c";
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("Cash");
		$this->getLogger()->info(TF::GREEN . "Load..");
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if ($cmd->getName() == "trem"){
		if(isset($args[0])){
				if(isset($args[0])){
				switch(strtolower($args[0])){
      case "list":
      $sender->sendMessage("§l§c<§6<§d-§f-§d-§f-§d-§f-§d-§e=§b=§4[§fรายละเอียดการเติมเงิน§4]§b=§e=§d-§f-§d-§f-§d-§f-§d-§6>§c>");
      $sender->sendMessage("§e");
      $sender->sendMessage("§l§a50 §f: §6ทรู§f/§eวอเลต §f-> §a10§f:§6เเคช §3| §aเงิน§f:§610§eM §3| §aมรกต§f:§d150 §3| §bเพรช§f:§e50");
      $sender->sendMessage("§f");
      $sender->sendMessage("§l§a90 §f: §6ทรู§f/§eวอเลต §f-> §a15§f:§6เเคช §3| §aเงิน§f:§615§eM §3| §aมรกต§f:§d300 §3| §bเพรช§f:§e90");
      $sender->sendMessage("§f");
      $sender->sendMessage("§l§a150 §f: §6ทรู§f/§eวอเลต §f-> §a30§f:§6เเคช §3| §aเงิน§f:§630§eM §3| §aมรกต§f:§d450 §3| §bเพรช§f:§e150");
      $sender->sendMessage("§l§b");
      $sender->sendMessage("§l§a300 §f: §6ทรู§f/§eวอเลต §f-> §a60§f:§6เเคช §3| §aเงิน§f:§660§eM §3| §aมรกต§f:§d900 §3| §bเพรช§f:§e300 §3| §aX§e2");
      $sender->sendMessage("§l§a");
	  $sender->sendMessage("§l§a500 §f: §6ทรู§f/§eวอเลต §f-> §a80§f:§6เเคช §3| §aเงิน§f:§680§eM §3| §aมรกต§f:§d1500 §3| §bเพรช§f:§e500");
	  $sender->sendMessage("§e§lเติมเงิน ได้ที่ §aLine §f: §6mnty §cเท่านั้น§6!!");
	  $sender->getLevel()->addSound(new GhastShootSound($sender));
      }
    }
  }
      if(isset($args[0])){
				if(isset($args[0])){
				switch(strtolower($args[0])){
				case "0":
				 $item1 = Item::get(0, 0, 1);
						 $name1 = $item1->setCustomName("§b§lกุญเเจ\n§fใช้สำหลับสุ่มของ\n§a§l+++");
 $money = $this->getServer()->getPluginManager()->getPlugin("Cash")->myCash($sender);
						  if ($money < 1){
							  $sender->sendTitle(TF::RED . "§c§lCash ไม่พอ");
						  }
						  else{
						   $item1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(10));
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
							 $item1->setCustomName($name1);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new GhastShootSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("Cash")->removeCash($sender->getName(), 1);
							  }
return true;
							  }
				}
		}
}
}
}