<?php

namespace Shopapi;

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
use pocketmine\level\sound\PopSound;
class Main extends PluginBase implements Listener{
	public $tag = "§c";
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->getLogger()->info(TF::GREEN . "Load..");
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if ($cmd->getName() == "sh"){
		if(isset($args[0])){
				if(isset($args[0])){
				switch(strtolower($args[0])){
      case "info":
      $sender->sendMessage("§l§c⚒§fM§7e§bL§do§6N§ay§7P§8E§c⚒");
      $sender->sendMessage("§f§lice §b: §eราคา §c: §a500§6$");
      $sender->sendMessage("§f§lลาวา §b: §eราคา §c: §a700§6$");
      $sender->sendMessage("§f§lดิน §b: §cX§610 §b: §eราคา §c: §a300§6$");
      $sender->sendMessage("§f§lหินเรียบ §b: §cX§616 §b: §eราคา §c: §a100§6$");
      $sender->sendMessage("§l§bกุญเเจ §f: §eราคา §c: §a20000§6$");
      $sender->sendMessage("§a§l[§fวิธีการซื้อ§a]\n§f/sh §a[§fตามด้วยชื่อนำหน้าITEM§a]\n§bที่คุณต้องการซื้อ");
      }
    }
  }
      if(isset($args[0])){
				if(isset($args[0])){
				switch(strtolower($args[0])){
				case "ice":
				 $item1 = Item::get(79, 0, 1);
 $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
						  if ($money < 500){
							  $sender->sendTitle(TF::RED . "§l§eเงินไม่พอ");
						  }
						  else{
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
						  $sender->sendTitle($this->tag. "§l§aซื้อสำเร็จ");
						  $sender->getLevel()->addSound(new PopSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($sender->getName(), 500);
							  }
 return true;
					  break;
				case "ลาวา":
				 $item1 = Item::get(10, 0, 1);
 $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
						  if ($money < 700){
							  $sender->sendTitle(TF::RED . "§l§eเงินไม่พอ");
						  }
						  else{
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
						  $sender->sendTitle($this->tag. "§l§aซื้อสำเร็จ");
						  $sender->getLevel()->addSound(new PopSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($sender->getName(), 700);
							  }
 return true;
					  break;
				case "ดิน":
				 $item1 = Item::get(2, 0, 10);
 $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
						  if ($money < 300){
							  $sender->sendTitle(TF::RED . "§l§eเงินไม่พอ");
						  }
						  else{
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
						  $sender->sendTitle($this->tag. "§l§aซื้อสำเร็จ");
						  $sender->getLevel()->addSound(new PopSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($sender->getName(), 300);
							  }
 return true;
					  break;
				case "หินเรียบ":
				 $item1 = Item::get(1, 0, 16);
						 $name1 = $item1->setCustomName("หินเรียบ");
 $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
						  if ($money < 100){
							  $sender->sendTitle(TF::RED . "§c§lเงินไม่พอ");
						  }
						  else{
						   $item1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(10));
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
							 $item1->setCustomName($name1);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new PopSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($sender->getName(), 100);
							  }
return true;
					  break;
				case "กุญเเจ":
				 $item1 = Item::get(399, 0, 1);
						 $name1 = $item1->setCustomName("§b§lกุญเเจ\n§fใช้สำหลับสุ่มของ\n§a§l+++");
 $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
						  if ($money < 20000){
							  $sender->sendTitle(TF::RED . "§c§lเงินไม่พอ");
						  }
						  else{
						   $item1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(10));
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
							 $item1->setCustomName($name1);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new PopSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($sender->getName(), 20000);
							  }
return true;
				case "bone":
				 $item1 = Item::get(352, 0, 16);
						 $name1 = $item1->setCustomName("");
 $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
						  if ($money < 750){
							  $sender->sendTitle(TF::RED . "§c§lเงินไม่พอ");
						  }
						  else{
						   $item1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(10));
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
							 $item1->setCustomName($name1);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new PopSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($sender->getName(), 100);
							  }
return true;
					  break;
				case "apple":
				 $item1 = Item::get(466, 0, 8);
						 $name1 = $item1->setCustomName("");
 $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
						  if ($money < 900000){
							  $sender->sendTitle(TF::RED . "§c§lเงินไม่พอ");
						  }
						  else{
						   $item1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(10));
							   $sender->getInventory()->addItem($item1);
							 //$sender->getInventory()->addItem($item6);
							 $item1->setCustomName($name1);
						  $sender->sendTitle($this->tag. "§l§aสำเร็จ");
						  $sender->getLevel()->addSound(new PopSound($sender));
						  
						  
							  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($sender->getName(), 100);
							  }
return true;
					  break;
							  }
				}
		}
}
}
}