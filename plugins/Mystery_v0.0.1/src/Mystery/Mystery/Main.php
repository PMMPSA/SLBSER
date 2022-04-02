<?php
namespace Mystery\Mystery;

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
	if ($event->getBlock ()->getId () == Item::TRAPPED_CHEST) {
			if ($player->getInventory ()->getItemInHand()->getId() == 83 ){
		$player->getInventory()->removeItem(Item::get(83,0,1));										
        $prize = rand(1,35);
        switch($prize){
        case 1:
          $item = Item::get(310,0,1);
		  $item->setCustomName("§l§4【§cห§aม§cว§aก§4】§f : §aด§eา§6ร§eา§7ก§fะ\n§f§l+ §7ป้องกัน §c: §680§a%\n§f+§7 ทนทาน §c: §e50§a%");
		  $item->addEnchantment(Enchantment::getEnchantment(0)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(1)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(2)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(5)->setLevel(80));
		  $player->getInventory()->addItem($item);
   	$this->getServer()->broadcastMessage("§c§l[§e $name §c] §f: §bสุ่ม §d§l❤§6M§ey§as§bt§de§er§fy§d❤ §eได้รับ §a: §l§4【§cห§aม§cว§aก§4】§f : §aด§eา§6ร§eา§7ก§fะ");
	$player->getLevel()->addSound(new TNTPrimeSound($event->getPlayer()));
        break;   
        case 3:
          $item = Item::get(311,0,1);
		  $item->setCustomName("§l§4【§cเ§6ส§cื§6้§cอ§4】§f : §aด§eา§6ร§eา§7ก§fะ\n§f§l+ §7ป้องกัน §c: §680§a%\n§f+§7 ทนทาน §c: §e50§a%");
		  $item->addEnchantment(Enchantment::getEnchantment(0)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(1)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(2)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(5)->setLevel(80));
		  $player->getInventory()->addItem($item);
		$this->getServer()->broadcastMessage("§c§l[§e $name §c] §f: §bสุ่ม §d§l❤§6M§ey§as§bt§de§er§fy§d❤ §eได้รับ §a: §l§4【§cเ§6ส§cื§6้§cอ§4】§f : §aด§eา§6ร§eา§7ก§fะ");
		$player->getLevel()->addSound(new TNTPrimeSound($event->getPlayer()));
        break;   
        case 4:
          $item = Item::get(312,0,1);
		  $item->setCustomName("§l§4【§cก§6า§cง§6เ§cก§6ง§4】§f : §aด§eา§6ร§eา§7ก§fะ\n§f§l+ §7ป้องกัน §c: §680§a%\n§f+§7 ทนทาน §c: §e50§a%");
		  $item->addEnchantment(Enchantment::getEnchantment(0)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(1)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(2)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(5)->setLevel(80));
		  $player->getInventory()->addItem($item);
		$this->getServer()->broadcastMessage("§c§l[§e $name §c] §f: §bสุ่ม §d§l❤§6M§ey§as§bt§de§er§fy§d❤ §eได้รับ §a: §l§4【§cก§6า§cง§6เ§cก§6ง§4】§f : §aด§eา§6ร§eา§7ก§fะ");
		$player->getLevel()->addSound(new TNTPrimeSound($event->getPlayer()));
        break;      
        case 5:
         $item = Item::get(313,0,1);
		  $item->setCustomName("§l§4【§cร§6อ§cง§6เ§cท§6้§cา§4】§f : §aด§eา§6ร§eา§7ก§fะ\n§f§l+ §7ป้องกัน §c: §680§a%\n§f+§7 ทนทาน §c: §e50§a%");
		  $item->addEnchantment(Enchantment::getEnchantment(0)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(1)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(2)->setLevel(80));
		  $item->addEnchantment(Enchantment::getEnchantment(5)->setLevel(80));
		  $player->getInventory()->addItem($item);
		$this->getServer()->broadcastMessage("§c§l[§e $name §c] §f: §bสุ่ม §d§l❤§6M§ey§as§bt§de§er§fy§d❤ §eได้รับ §a: §l§4【§cร§6อ§cง§6เ§cท§6้§cา§4】§f : §aด§eา§6ร§eา§7ก§fะ");
		$player->getLevel()->addSound(new TNTPrimeSound($event->getPlayer()));
        break;     
    } 
		} else {
			$player->sendTitle ( "§b§lต้องการ\n§d§l❤§6M§ey§as§bt§de§er§fy§d❤" );   
			$event->setCancelled ( true );
}
}
}
}