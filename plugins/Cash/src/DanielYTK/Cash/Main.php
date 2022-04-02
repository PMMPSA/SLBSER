<?php
namespace DanielYTK\Cash;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\utils\Config;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\event\player\PlayerItemHeldEvent;

class Main extends PluginBase implements Listener{
    public function onEnable(){
        $this->getServer()->getLogger()->info("§9Cash§f - §aHabilitado");
        $this->getServer()->getLogger()->info("§dBy DanielYTK");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
		$this->cmd = new Config($this->getDataFolder(). "comandos.yml", Config::YAML, [
		"cash" => "para ver o seu cash",
		"setcash" => "para setar o cash dos jogadores",
		"addcash" => "para adicionar cash a conta de um jogador",
		"takecash" => "para remover cash da conta de um jogador",
		]);
		$this->cmd->save();
    }
    public function onJoin(PlayerJoinEvent $ev){
        $player = $ev->getPlayer()->getName();
        $this->money = new Config($this->getDataFolder()."cash.yml",Config::YAML, [
            strtolower($player) => 0,
        ]);
        $this->money->save();
        }
		public function addCash($player, $cash){
			if($player instanceof Player){
				$player = $player->getName();
			}
			$player = strtolower($player);
			$money = new Config($this->getDataFolder()."cash.yml",Config::YAML);
			$money->set($player, (int)$money->get($player) +$cash);
			$money->save();
		}
		public function removeCash($player, $cash){
			if($player instanceof Player){
				$player = $player->getName();
			}
			if($this->myCash($player) - $cash < 0){
				return true;
			}
			$player = strtolower($player);
			$money = new Config($this->getDataFolder()."cash.yml",Config::YAML);
			$money->set($player, (int)$money->get($player) -$cash);
			$money->save();
		}
		public function myCash($player){
			if($player instanceof Player){
				$player = $player->getName();
			}
			$player = strtolower($player);
			$money = new Config($this->getDataFolder()."cash.yml",Config::YAML);
			$money->get($player);
			return $money->get($player);
		}
        public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
            switch($command->getName()){
                case "cash":
                    $money = new Config($this->getDataFolder()."cash.yml",Config::YAML);
                    $sender->sendMessage("§e§lเเคช §f: §6".$money->get(strtolower($sender->getName())));
                    break;
                case "setcash":
				if(!isset($args[1])){
					$sender->sendMessage("§eO cash ou nick não foram definidos!");
					return true;
				}
				if(!is_numeric($args[1])){
					$sender->sendMessage("§eO valor de cash deve ser númerico!");
					return true;
				}
                    $player = $this->getServer()->getPlayer($args[0]);
                    $money = new Config($this->getDataFolder()."cash.yml",Config::YAML);
                    if(!isset($args[1])){
                        $sender->sendMessage("§6Use: §f/setcash <player> <cash>");
                        return;
                    }
                    $nick = strtolower($player->getName());
                    $money->set($nick, (int) $args[1]);
                    $money->save();
                    $sender->sendMessage("§6Você setou o cash de §f".$player->getName()." §6para §f".$args[1] ."");
                    $player->sendMessage("§6Seu cash foi setado para: §f".$args[1]);
                    break;
                case "addcash":
				if(!isset($args[1])){
					$sender->sendMessage("§eO cash ou nick não foram definidos!");
					return true;
				}
				if(!is_numeric($args[1])){
					$sender->sendMessage("§eO valor de cash deve ser númerico!");
					return true;
				}
                    $player = $this->getServer()->getPlayer($args[0]);
                    $money = new Config($this->getDataFolder()."cash.yml", Config::YAML);
                    if(!isset($args[1])){
                        $sender->sendMessage("§6Use:§f /addcash <player> <cash>");
                        return;
                    }
                    $nick = strtolower($player->getName());
                    $money->set($nick, $money->get($nick) +$args[1]);
                    $money->save();
                    $sender->sendMessage("§6Você adicionou §f".$args[1] ." §6cash para §f".$player->getName()."");
                    $sender->sendMessage("§6O cash total de §f".$player->getName()." §6 é: §f".$money->get(strtolower($player->getName())));
                    $player->sendMessage("§6Foi adicionado §f".$args[1]."§6 de cash a sua conta");
					break;
                case "takecash":
				if(!isset($args[1])){
					$sender->sendMessage("§eO cash ou nick não foram definidos!");
					return true;
				}
				if(!is_numeric($args[1])){
					$sender->sendMessage("§eO valor de cash deve ser númerico!");
					return true;
				}
                    $player = $this->getServer()->getPlayer($args[0]);
                    $money = new Config($this->getDataFolder()."cash.yml",Config::YAML);
                    $nick = strtolower($player);
                    $money->set($nick, $money->get($nick) -$args[1]);
                    $money->save();
                    $sender->sendMessage("§6Você retirou §f".$args[1] ."§6 de cash, de §f".$player->getName()."");
                    $sender->sendMessage("§6O cash total de §f".$player->getName()." §6 é: ".$money->get(strtolower($player->getName())));
                    $player->sendMessage("§6Foi retirado §f".$args[1]." §6de cash da sua conta");
            }
        }
		public function onHeld(PlayerItemHeldEvent $ev){
			$item = $ev->getItem()->getId();
		}
}