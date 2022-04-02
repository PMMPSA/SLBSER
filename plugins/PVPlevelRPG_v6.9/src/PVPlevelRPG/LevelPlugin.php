<?php

namespace PVPlevelRPG;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\level\Position;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\entity\AttributeManager;
class LevelPlugin extends PluginBase implements Listener{
public function onEnable(){
if(!file_exists($this->getDataFolder())){
    mkdir($this->getDataFolder(), 0744, true);
}
$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML,
array(
        'Exp that Nowak when defeated' => '50',
        '1 level level up necessary Exp of' => '50',
        'The amount of increase sometimes necessary Exp when you went up level' => '50',
        'Number to increase the number get Exp If you continuously kill' => '10',
        'Lowercase save (save even if the player name is changed case as the same player)' => 'on',
        'Level up at the time of the message (individual or all)' => '個人',
        'Examine other players level at the command' => 'on',
        'Level of tag display' => 'on',
        'Level of display display' => 'on',
        'Kills Death number display in the / level' => 'on',
        'You need Exp automatic correction' => 'on',
	'Experience bar display' => 'off'
));
$this->exp = new Config($this->getDataFolder() . "Exp.yml", Config::YAML,
array(
));
$this->level = new Config($this->getDataFolder() . "Level.yml", Config::YAML,
array(
));
$this->levelup = new Config($this->getDataFolder() . "Levelup.yml", Config::YAML,
array(
));
$this->killup = new Config($this->getDataFolder() . "killup.yml", Config::YAML,
array(
));
$this->expup = new Config($this->getDataFolder() . "expup.yml", Config::YAML,
array(
));
$this->kill = new Config($this->getDataFolder() . "kill.yml", Config::YAML,
array(
));
$this->death = new Config($this->getDataFolder() . "death.yml", Config::YAML,
array(
));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
public function onJoin(PlayerJoinEvent $event){
$player = $event->getPlayer();
$user = $player->getName();
$user2 = $event->getPlayer()->getName();
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user = strtolower($user);
}
if(!$this->exp->exists($user)){
$this->exp->set($user,"0");
$this->exp->save();
}
$this->expup->set($user,$this->config->get("Nowak when defeated Exp"));
$this->expup->save();
if(!$this->level->exists($user)){
$this->level->set($user,"1");
$this->level->save();
}

if(!$this->levelup->exists($user)){
$this->levelup->set($user,$this->config->get("1 level level up necessary Exp of"));
$this->levelup->save();
}
if(!$this->kill->exists($user)){
$this->kill->set($user,"0");
$this->kill->save();
}
if(!$this->death->exists($user)){
$this->death->set($user,"0");
$this->death->save();
}
if($this->config->get("You need Exp automatic correction") == "on"){
$a = $this->level->get($user) - 1;
$b = $this->config->get("The amount of increase sometimes necessary Exp when you went up level") * $a + $this->config->get("1 level level up necessary Exp of");
$this->levelup->set($user,$b);
$this->levelup->save();
}
if($this->config->get("Level of tag display") == "off"){
$player->setNameTag("§4[§bL§3v§f.§6".$this->level->get($user)."§4]§f ".$user2);
$player->save();
}
if($this->config->get("Level of display display") == "on"){
$player->setDisplayName("§4[§bL§3v§f.§6".$this->level->get($user)."§4]§f ".$user2);
$player->save();
}
}
public function onPlayerKill(PlayerDeathEvent $event) {
$ev = $event->getEntity()->getLastDamageCause();
if ($ev instanceof EntityDamageByEntityEvent) {
$player = $ev->getDamager();
$user = $player->getName();
$user2 = $user;
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user2 = strtolower($user);
}
if($player instanceof Player) {
   $this->exp->set($user2,$this->exp->get($user2) + $this->expup->get($user2));
   $this->exp->save();

if($this->config->get("Level of display display") == "on"){
$player->setNameTag("§4[§bL§3v§f.§6".$this->level->get($user2)."§4]§f ".$user);
$player->save();
}
if($this->config->get("Level of display display") == "on"){
$player->setDisplayName("§4[§bL§3v§f.§6".$this->level->get($user2)."§4]§f ".$user);
$player->save();
}
if($this->exp->get($user2) >= $this->levelup->get($user2)){
    $this->level->set($user2,$this->level->get($user2) + 1);
    $this->level->save();
    $this->exp->set($user2,"0");
    $this->exp->save();
    $this->levelup->set($user2,$this->levelup->get($user2) + $this->config->get("The amount of increase sometimes necessary Exp when you went up level"));
    $this->levelup->save();

if($this->config->get("Level of display display") == "on"){
$player->setNameTag("§4[§bL§3v§f.§6".$this->level->get($user2)."§4]§f ".$user);
$player->save();
}
if($this->config->get("Level of display display") == "on"){
$player->setDisplayName("§4[§bL§3v§f.§6".$this->level->get($user2)."§4]§f ".$user);
$player->save();
}
if($this->config->get("Level up at the time of the message (individual or all)") == "個人"){
// msg passou de nível
$player->sendMessage("§eเวลอัป §4[§bL§3v§f.§6".$this->level->get($user2)."§4]");
}else{
Server::getInstance()->broadcastMessage("".$user."Is level".$this->level->get($user2)."I went up to");
}
}else{
$exp = $this->levelup->get($user2) - $this->exp->get($user2);
// msg mesmo nivel 
$player->sendMessage("");
if($this->config->get("Level of display display") == "on"){
$player->setNameTag("§4[§bL§3v§f.§6".$this->level->get($user2)."§4]§f ".$user);
$player->save();
}
if($this->config->get("Level of display display") == "on"){
$player->setDisplayName("§4[§bL§3v§f.§6".$this->level->get($user2)."§4]§f ".$user);
$player->save();
}
}
$this->expup->set($user2,$this->expup->get($user2) + $this->config->get("Number to increase the number get Exp If you continuously kill"));
$this->expup->save();
}
}
}
public function onPlayerKillDeath(PlayerDeathEvent $event) {
$ev = $event->getEntity()->getLastDamageCause();
$player = $event->getEntity();
$user = $player->getName();
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user = strtolower($player->getName());
}
$this->expup->set($user,$this->config->get("Exp that Nowak when defeated"));
$this->expup->save();
if($this->config->get("Kills Death number display in the / level") == "on"){
$this->death->set($user,$this->death->get($user) + 1);
$this->death->save();
}
if ($ev instanceof EntityDamageByEntityEvent) {
$player2 = $ev->getDamager();
$user2 = $player2->getName();
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user2 = strtolower($player2->getName());
}
if($this->config->get("Kills Death number display in the / level") == "on"){
$this->kill->set($user2,$this->kill->get($user2) + 1);
$this->kill->save();
}
}
}
public function LevelUp(PlayerMoveEvent $event){
if($this->config->get("Experience bar display") == "on"){
$player = $event->getPlayer();
$user = $player->getName();
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user = strtolower($user);
}
$player->getAttribute()->getAttribute(AttributeManager::EXPERIENCE_LEVEL)->setValue($this->level->get($user));
$player->getAttribute()->getAttribute(AttributeManager::EXPERIENCE)->setValue($this->exp->get($user)/$this->levelup->get($user));
}
}
public function getExp($user) {
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user = strtolower($user);
}
if($this->exp->exists($user)){
return $this->exp->get($user);
}else{
$this->exp->set($user,"0");
$this->exp->save();
return 0;
}
}
public function setExp($user,$exp) {
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user = strtolower($user);
}
if($this->exp->exists($user)){
   $this->exp->set($user,$exp);
   $this->exp->save();
if($this->exp->get($user) >= $this->levelup->get($user)){
    $this->level->set($user,$this->level->get($user) + 1);
    $this->level->save();
    $this->exp->set($user,"0");
    $this->exp->save();
    $this->levelup->set($user,$this->levelup->get($user) + $this->config->get("The amount of increase sometimes necessary Exp when you went up level"));
    $this->levelup->save();
}
}else{
$this->exp->set($user,$exp);
$this->exp->save();
}
}
public function getLevel($user) {
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user = strtolower($user);
}
if($this->level->exists($user)){
return $this->level->get($user);
}else{
$this->level->set($user,"1");
$this->level->save();
return 1;
}
}
public function getLevelUp($user) {
if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
$user = strtolower($user);
}
if($this->levelup->exists($user)){
return $this->levelup->get($user);
}else{
$this->levelup->set($user,$this->config->get("1 level level up necessary Exp of"));
$this->level->save();
return $this->config->get("1 level level up necessary Exp of");
}
}

public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
	switch (strtolower($command->getName())) {
		case "level":
			if($this->config->get("Examine other players level at the command") == "on"){
			if(!isset($args[0])){
			$user = $sender->getName();
			if($this->config->get("Lowercase save (save the player's name as the same player also changed case)") == "on"){
			$user = strtolower($user);
			}
			$exp = $this->levelup->get($user) - $this->exp->get($user);
			$sender->sendMessage("§cuse: /level <nome>");
			}else{
			$user = $args[0];
			if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
			$user = strtolower($user);
			}
			if($this->level->exists($user)){
			$exp = $this->levelup->get($user) - $this->exp->get($user);
			$sender->sendMessage("§b--------------------");
			$sender->sendMessage("§eNome:§f ".$args[0]."");
			$sender->sendMessage("§1§eความโหด:§f ".$this->level->get($user)."");
			$sender->sendMessage("§2Exp:§f ".$this->exp->get($user)."/".$this->levelup->get($user)."");
			$sender->sendMessage("§2Exp necessário para subir de nível:§f ".$exp."");
			if($this->config->get("Kills Death number display in the / level") == "on"){
			$sender->sendMessage("§aKills: §f".$this->kill->get($user)."");
			$sender->sendMessage("§cDeaths: §f".$this->death->get($user)."");
			$sender->sendMessage("§b--------------------");
			}
			}else{
			$player = $this->getServer()->getPlayer($args[0]);
			if($player instanceOf Player){
			$user = $player->getPlayer()->getName();
			$user1 = $player->getPlayer()->getName();
			if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
			$user1 = strtolower($user1);
			}
			$exp = $this->levelup->get($user1) - $this->exp->get($user1);
			$sender->sendMessage("§b--------------------");
			$sender->sendMessage("§eNome:§f ".$user."");
			$sender->sendMessage("§eความโหด:§f ".$this->level->get($user1)."");
			$sender->sendMessage("§2Exp:§f ".$this->exp->get($user1)."/".$this->levelup->get($user1)."");
			$sender->sendMessage("§2Exp necessário para subir de nível: §f".$exp."");
			if($this->config->get("Kills Death number display in the / level") == "on"){
			$sender->sendMessage("§aKills: §f".$this->kill->get($user1)."");
			$sender->sendMessage("§cDeaths: §f".$this->death->get($user1)."");
			$sender->sendMessage("§b--------------------");
			}
			}else{
			$sender->sendMessage("§b ".$args[0]." §cEu nunca esteve nesse servidor");
				$max = 0;
				foreach($this->level->getAll() as $d){
					$max += count($d);
				}

				$max = ceil(($max / 5));

				$page = array_shift($params);

				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;

				$current = 1;
				$n = 1;

				$output = "Salary list $page / $max display: \ n";
				$sender->sendMessage($output);

			}
			}
			}
			}else{
			$user = $sender->getName();
			if($this->config->get("Lowercase save (save even if the player name is changed case as the same player)") == "on"){
			$user = strtolower($user);
			}
			$exp = $this->levelup->get($user) - $this->exp->get($user);
			$sender->sendMessage("§b--------------------");
			$sender->sendMessage("§eNome:§f ".$user."");
			$sender->sendMessage("§eความโหด:§f ".$this->level->get($user)."");
			$sender->sendMessage("§2Exp:§f ".$this->exp->get($user)."/".$this->levelup->get($user)."");
			$sender->sendMessage("§2Exp necessário para subir de nível:§f ".$exp."");
			if($this->config->get("Kills Death number display in the / level") == "on"){
			$sender->sendMessage("§aKills: §f".$this->kill->get($user)."");
			$sender->sendMessage("§cDeaths: §f".$this->death->get($user)."");
			$sender->sendMessage("§b--------------------");
			}
			}
			break;
		case "tophell":
				$max = 0;
				foreach($this->level->getAll() as $c){
				$max += count($c);
				}
				$max = ceil(($max / 5));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$sender->sendMessage("§e§l• §cอ§6ั§eน§aด§bั§dบ §dค§fว§dา§fม§dโ§fห§dด §e•§r(".$page." §e§6ต่อe§f ".$max.") -");
				$aa = $this->level->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
				$i1 = $i + 1;
				$sender->sendMessage("§a".$i1." §f".$b.":§e ".$a);
				}
				$i++;
				}
			break;
		case "topkills":
			if($this->config->get("Kills Death number display in the / level") == "on"){
				$max = 0;
				foreach($this->kill->getAll() as $c){
				$max += count($c);
				}
				$max = ceil(($max / 5));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$sender->sendMessage("§l§dอ§6ั§dน§6ด§dั§6บ §aก§bา§aร§bฆ§a่§bา§c (".$page." §e§e§lต่อ§c ".$max.") -");
				$aa = $this->kill->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
				$i1 = $i + 1;
				$sender->sendMessage("§a•§e".$i1."§a• §f".$b.":§3 ".$a);
				}
				$i++;
				}
				}else{
				$sender->sendMessage("§ccontagem Kills é inválido");
				}
			break;
		case "topdeaths":
			if($this->config->get("Kills Death number display in the / level") == "on"){
				$max = 0;
				foreach($this->death->getAll() as $c){
				$max += count($c);
				}
				$max = ceil(($max / 5));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$sender->sendMessage("§c§lอ§6ั§cน§6ด§cั§6บ §bก§fา§bร§4ตOl§fา§4ย§c (".$page." §4§lต่อ§c ".$max.") -");
				$aa = $this->death->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
				$i1 = $i + 1;
				$sender->sendMessage("§c•§4".$i1."§c• §f".$b.":§6 ".$a);
				}
				$i++;
				}
				}else{
				$sender->sendMessage("§ccontagem de morte é inválido");
				}
			return true;
			break;
	}
	return false;
}

}