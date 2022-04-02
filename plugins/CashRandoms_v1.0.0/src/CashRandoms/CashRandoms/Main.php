<?php
namespace CashRandoms\CashRandoms;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\enchantment\Enchantment;

use pocketmine\utils\Config;

use pocketmine\item\Item;
use pocketmine\Inventory;
use pocketmine\item\Emerald;

use pocketmine\block\Block;
use pocketmine\level\sound\TNTPrimeSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\utils\TextFormat as C;

use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\PopSound;


class Main extends PluginBase implements Listener{

  public function onEnable(){
    @mkdir($this->getDataFolder());
    $config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $config->save();
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }

  public function onInteract(PlayerInteractEvent $event){
    $block = $event->getBlock();
    $player = $event->getPlayer();
	$name = $player->getName();
	if ($event->getBlock ()->getId () == Item::DRAGON_EGG) {
			if ($player->getInventory ()->getItemInHand()->getId() == 399 ){
		$this->getServer()->broadcastMessage("§a$name §b§lได้ทำการ§dสุ่ม §aกล่อง§6เทพมร§eณะ§a!");
		$player->sendMessage ( "§6§lของอยู่ในตัวคุณแล้ว");
		$player->getInventory()->removeItem(Item::get(399,0,1));										
        $prize = rand(1,20);
        switch($prize){
        case 1:
          $item = Item::get(276,0,1);
		  $item->setCustomName("§l§o§e★ §cS§6w§ea§ar§bd §d+§b3 §e★");
		  $item->addEnchantment(Enchantment::getEnchantment(9)->setLevel(3));
		  $item->addEnchantment(Enchantment::getEnchantment(10)->setLevel(3));
		  $item->addEnchantment(Enchantment::getEnchantment(12)->setLevel(3));
		  $item->addEnchantment(Enchantment::getEnchantment(13)->setLevel(3));
		  $item->addEnchantment(Enchantment::getEnchantment(20)->setLevel(3));
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new AnvilFallSound($event->getPlayer()));
        break;
        case 2:
          $item = Item::get(368,0,6);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 3:
          $item = Item::get(368,0,9);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 5:
          $item = Item::get(289,0,5);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 6:
          $item = Item::get(289,0,9);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 7:
          $item = Item::get(466,0,3);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 8:
          $item = Item::get(466,0,1);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 9:
          $item = Item::get(81,0,5);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 10:
          $item = Item::get(397,1,1);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 11:
          $item = Item::get(373,22,1);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 12:
          $item = Item::get(373,33,1);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 13:
          $item = Item::get(17,0,3);
		  $item->setCustomName("");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 14:
          $item = Item::get(445,0,3);
		  $item->setCustomName("§f• §bชิ้นส่วนอัปเกรด §f•");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
        case 15:
          $item = Item::get(445,0,1);
		  $item->setCustomName("§f• §bชิ้นส่วนอัปเกรด §f•");
		  $player->getInventory()->addItem($item);
		  $player->getLevel()->addSound(new PopSound($event->getPlayer()));
        break;
    } 
		} else {
			$player->sendTitle ( "§e§lต้องการกุณเเจ§c!" );   
			$event->setCancelled ( true );
}
}
}
}