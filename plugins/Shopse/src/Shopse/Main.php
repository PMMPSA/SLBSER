<?php

namespace shopse;

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
		if ($cmd->getName() == "shops"){
		if(isset($args[0])){
				if(isset($args[0])){
				switch(strtolower($args[0])){
      case "info":
      $sender->sendMessage("§l§c-§6=§c{ §a⚒ §eร้านค้า §6C§fa§6s§fh §a⚒ §c}§6=§c-");
      $sender->sendMessage("§b[§e1§b] §f: §eกุญเเจมรณะ §aราคา §f: §e1 §6Cash");
      $sender->sendMessage("§b[§e2§b] §f: §b⚒ §l§cเ§6อ§cก§6ซ§c์§6ค§cา§6ล§cิ§6เ§cบ§6อ§cร§6์§b ⚒ §aราคา §f: §e4 §6Cash");
      $sender->sendMessage("§b[§e3§b] §f: §bเพรช §f: §e64§6ชิ้น §f: §aราคา §f: §e2 §6Cash");
      $sender->sendMessage("§b[§e4§b] §f: §6ป§aี§6ก§aม§6ั§aง§6ก§aร §f: §aราคา §f: §e3 §6Cash");
      $sender->sendMessage("§l§b");
      $sender->sendMessage("§a§l[§fวิธีการซื้อ§a]\n§f/shops §a[§fตามด้วยชื่อนำหน้าITEM§a]\n§bที่คุณต้องการซื้อ\n§eเช่น §f/shops 1\n");
      }
    }
  }
      if(isset($args[0])){
				if(isset($args[0])){
				switch(strtolower($args[0])){
				case "1":
				 $item1 = Item::get(399, 0, 1);
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
					  break;
				case "2":
				 $item1 = Item::get(276, 0, 1);
						 $name1 = $item1->setCustomName("§b⚒ §l§cเ§6อ§cก§6ซ§c์§6ค§cา§6ล§cิ§6เ§cบ§6อ§cร§6์§b ⚒\n§eระดับ §f: §4ตำนาน");
 $money = $this->getServer()->getPluginManager()->getPlugin("Cash")->myCash($sender);
						  if ($money < 4){
							  $sender->sendTitle(TF::RED . "§c§lCash ไม่พอ");
						  }
						  else{
						   $item1->addEnchantment(Enchantment::getEnchantment(9)->setLevel(20));
						   $item1->addEnchantment(Enchantment::getEnchantment(10)->setLevel(20));
						   $item1->addEnchantment(Enchantment::getEnchantment(11)->setLevel(20));
						   $item1->addEnchantment(Enchantment::getEnchantment(12)->setLevel(20));
						   $item1->addEnchantment(Enchantment::getEnchantment(13)->setLevel(20));
						   $item1->addEnchantment(Enchantment::getEnchantment(17)->setLevel(20));
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
							 $item1->setCustomName($name1);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new GhastShootSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("Cash")->removeCash($sender->getName(), 4);
							  }
return true;
					  break;
				case "3":
				 $item1 = Item::get(264, 0, 64);
 $money = $this->getServer()->getPluginManager()->getPlugin("Cash")->myCash($sender);
						  if ($money < 2){
							  $sender->sendTitle(TF::RED . "§c§lCash ไม่พอ");
						  }
						  else{
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new GhastShootSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("Cash")->removeCash($sender->getName(), 2);
							  }
return true;
					  break;
				case "4":
				 $item1 = Item::get(444, 0, 1);
				 $name1 = $item1->setCustomName("§6ป§aี§6ก§aม§6ั§aง§6ก§aร\n§eระดับ §f: §4ตำนาน");
 $money = $this->getServer()->getPluginManager()->getPlugin("Cash")->myCash($sender);
						  if ($money < 3){
							  $sender->sendTitle(TF::RED . "§c§lCash ไม่พอ");
						  }
						  else{
							$item1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(99));
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
							 $item1->setCustomName($name1);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new GhastShootSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("Cash")->removeCash($sender->getName(), 3);
							  }
return true;
							  }
				}
		}
}
}
}