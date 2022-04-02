<?php
namespace KillAlert;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat as TT;
use pocketmine\entity\Living;
use pocketmine\math\Vector3;
use pocketmine\level\Position;


class Main extends PluginBase implements Listener {
	
	const PREFIX = TT::DARK_GRAY."§f".TT::RED."".TT::YELLOW."".TT::DARK_GRAY."§f" . TT::WHITE." ";
 
    
  public function onEnable()
  {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
   $this->getLogger()->info("KillAlerts has been enabled.");
 
           
    }
    
	public function onDeath(PlayerDeathEvent $ev){
		$player = $ev->getEntity();
		$cause = $player->getLastDamageCause();
		$ev->setDeathMessage(null);
		if($player instanceof Player){
		switch($cause === null ? EntityDamageEvent::CAUSE_CUSTOM : $cause->getCause()){
				case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
					if($cause instanceof EntityDamageByEntityEvent){
						$e = $cause->getDamager();
						$item = $e->getItemInHand();
						$itemname = $item->getName();
						if($e instanceof Player){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §6§lโดน§r §f".$e->getName()." §c§lฆ่า§eโดย §3[§e $itemname §3]");
							break;
						}elseif($e instanceof Living){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §6§lโดน§r§f ".$e->getName()." §c§lฆ่า§4ตาย");
							break;
						}else{
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §c§lตาย§eโดย§aไม่มี§6สาเหตุ");
						}
					}
					break;
				case EntityDamageEvent::CAUSE_PROJECTILE:
					if($cause instanceof EntityDamageByEntityEvent){
						$e = $cause->getDamager();
						if($e instanceof Player){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cถู§bก§cยิ§bง§eโ§aด§6ย§r§f ".$e->getName()." §l§cโ§bด§eย§aใ§6ช้§cธ§bน§aใ§6น§dก§bา§eร§cฆ§6่§eา");
						}elseif($e instanceof Living){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cต§bา§eย §aโ§fด§6ย §eธ§aนู§c§r");
							break;
						}else{
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cต§bา§eย §aโ§fด§6ย §eธ§aนู§c§r");
						}
					}
					break;
				case EntityDamageEvent::CAUSE_SUICIDE:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cต§bา§eย");
					break;
				case EntityDamageEvent::CAUSE_VOID:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §e§lตกโลก§cตาย");
					break;
				case EntityDamageEvent::CAUSE_FALL:
					if($cause instanceof EntityDamageEvent){
						if($cause->getFinalDamage() > 2){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§6ตกจากที่สูง§cตาย");
							break;
						}
					}
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §c§lตาย§eโดย§aไม่มี§6สาเหตุ");
					break;

				case EntityDamageEvent::CAUSE_SUFFOCATION:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§eติดบัค§cตาย");
					break;

				case EntityDamageEvent::CAUSE_LAVA:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§eตกลาวา§cตาย");
					break;

				case EntityDamageEvent::CAUSE_FIRE:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§eไฟไหม้ §cตาย");
					break;

				case EntityDamageEvent::CAUSE_FIRE_TICK:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§eไฟไหม้ตัว §cตาย");
					break;

				case EntityDamageEvent::CAUSE_DROWNING:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cจ§bม§eน้§6ำ§cต§fา§6ย");
					break;

				case EntityDamageEvent::CAUSE_CONTACT:
					if($cause instanceof EntityDamageByBlockEvent){
						if($cause->getDamager()->getId() === Block::CACTUS){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cต§bา§eย§aโ§fด§6ย§cก§bร§eะ§aบ§6อ§cก§bเ§cพ§bร§eช§r");
						}
					}
					break;

				case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
				case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
					if($cause instanceof EntityDamageByEntityEvent){
						$e = $cause->getDamager();
						if($e instanceof Player){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cถู§bก§eร§aอ§6บ§cยิ§bง§eโ§aด§6ย§f§r ".$e->getName()." §l§aใ§6ช้§cธ§bนู §aย§6ิ§aง§6จ§aน§cตาย");
						}elseif($e instanceof Living){
							$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §l§cต§bา§eย§aโ§fด§6ย§cร§bะ§eเ§aบิ§fด§6§c§r");
							break;
						}
					}else{
						$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §c§lตาย§eโดย§aไม่มี§6สาเหตุ");
					}
					break;

				case EntityDamageEvent::CAUSE_MAGIC:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §c§lตาย§eโดย§aไม่มี§6สาเหตุ");
					break;

				case EntityDamageEvent::CAUSE_CUSTOM:
					$this->getServer()->broadcastMessage(Main::PREFIX.$player->getName()." §c§lตาย§eโดย§aไม่มี§6สาเหตุ");
					break;
		}
	}
 }
}
