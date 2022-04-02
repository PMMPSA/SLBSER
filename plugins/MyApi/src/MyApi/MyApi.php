<?php

namespace MyApi;

use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\plugin\Plugin;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\inventory\PlayerInventory;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\inventory\BaseInventory;
use pocketmine\item\enchantment\Enchantment;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\level\sound\ClickSound;
use pocketmine\scheduler\CallbackTask;
use pocketmine\utils\Config;
use pocketmine\level\sound\EndermanTeleportSound;
use _64FF00\PurePerms\PPGroup;

class MyApi extends PluginBase implements Listener{

public function onEnable()
	{
     $this->getServer()->getPluginManager()->registerEvents($this ,$this);
}
  public function onCommand(CommandSender $sender,Command $cmd,$label,array $args){

	  if($cmd->getName() == "myapi"){
		  if(!isset($args[0])){
			  $sender->sendmessage("§f[§6Tor§f]§6 Use §7/tor comprar§6 Para Ter Acesso Ao Machado Do Tor !!! ");
			  $sender->sendmessage("§f[§6Tor§f]§6 Comprando O Machado Ao Voce Colocar Na Mao Voce Podera Voar e Soltar Raios em Seus Inimigos !");
			  $sender->sendmessage("§f[§6Tor§f]§4 mas tome cuidado,§6 ao retirar o machado da mao voce perdera os seus poderes !");

		  } else {
			  if($args[0] == "1"){
				        $money = EconomyAPI::getInstance()->myMoney($sender);
				        if(2000000 <= $money){
				        $item = Item::get(83,0,1);
                        $item->setCustomName("§d§l❤§6M§ey§as§bt§de§er§fy§d❤");
						$item->addEnchantment(Enchantment::getEnchantment(9)->setLevel(60));
                        $sender->getInventory()->addItem($item);
                        $sender->sendMessage("§a§lซื้อ Mystery สำเร็จ");
                          EconomyAPI::getInstance()->reduceMoney($sender, 2000000);
			  } else {
$sender->sendmessage("§c§lคุณมีเงินไม่พอ");			  }
			  } else {
			  $sender->sendmessage("§f[§6Tor§f]§6 Use §7/tor comprar§6 Para Ter Acesso Ao Machado Do Tor !!! ");
			  $sender->sendmessage("§f[§6Tor§f]§6 Comprando O Machado Ao Voce Colocar Na Mao Voce Podera Voar e Soltar Raios em Seus Inimigos !");
			  $sender->sendmessage("§f[§6Tor§f]§4 mas tome cuidado,§6 ao retirar o machado da mao voce perdera os seus poderes !");
			  }
	  }
	  }
  }
  /*public function Move(PlayerMoveEvent $event){
	  $player = $event->getPlayer();
		$item = $player->getIteminHand();
	  if($item->getName() !== "§eMachado Do §bТоr" && $player->getAllowFlight() == true){
	  $player->setAllowFlight(false);
	  }
	  if($player->getAllowFlight() == true) return;
	  if($item->getName() == §c§l❤§eE§an§dd§6e§fr §eS§6w§ao§fr§ed§c❤\n§f▶§eเมื่อโจมตี จะเกิดเสียง\n§f▶§6ดาเมจ §c-§e0"){
		   $player->setAllowFlight(true);
	  }

  }*/
  public function TorDamage(EntityDamageEvent $event){
	  if($event instanceof EntityDamageByEntityEvent){
		    $damager = $event->getDamager();
			$player = $event->getEntity();
			$item = $damager->getIteminHand();
			if($item->getName() == "§d§l❤§6M§ey§as§bt§de§er§fy§d❤"){
			  $player->getLevel()->addSound(new ClickSound($player));
	  }
}
  }
  public function TorBreak(PlayerInteractEvent $e){
	  	   $p = $e->getPlayer();
		$item = $p->getIteminHand();
	   if($item->getName() == "§d§l❤§6M§ey§as§bt§de§er§fy§d❤"){

		 $p->getLevel()->addSound(new ClickSound($p));
  }
}
}


?>