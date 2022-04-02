<?php
namespace Buffalo\Buffalo;

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
use pocketmine\level\sound\GhastShootSound;
use pocketmine\level\sound\PopSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\utils\TextFormat as C;

use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\particle\LavaParticle;


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
	if ($event->getBlock ()->getId () == Item::STONECUTTER) {
			if ($player->getInventory ()->getItemInHand()->getId() == 421 ){
		$player->getInventory()->removeItem(Item::get(421,0,1));										
        $prize = rand(1,1000);
        switch($prize){
        case 1:
          $item = Item::get(283,0,1);
		  $item->setCustomName("§6§l⚒§eG§ao§el§ad§bS§fw§bo§fr§bd §4+§c1000§6⚒");
		  $item->addEnchantment(Enchantment::getEnchantment(9)->setLevel(1000));
		  $item->addEnchantment(Enchantment::getEnchantment(17)->setLevel(1000));
		  $player->getInventory()->addItem($item);
   	$this->getServer()->broadcastMessage("§e§l⚒§cเ§6เ§eจ§a๊§bก§fพ§dอ§7ต §9เ§eเ§aต§bก!!! §e§l[§6 $name §e] §eสุ่มได้ดาบ §c+§a1000");
	$player->getLevel()->addSound(new GhastShootSound($event->getPlayer()));
        break;     
    } 
		} else {
			$player->sendTitle ( "§b§lต้องการ\n§d§lลอตเตอรี่" );   
			$event->setCancelled ( true );
}
}
}
}