<?php

namespace bau;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\item\Item;

use bau\inventory\ChestInv;
use bau\task\Delayed;
use bau\task\Update;
use bau\task\Close;

class Main extends PluginBase implements Listener{
	
	public $inv = [];
	public $open = [];
	public $chest = [];
	public $close = [];
	public $all = [];
	public $bau = [];
	public $pag = [];
	public $aum = [];
	public $icone = [];
	public $icones = ["1" => ["279", "278", "276", "277", "293"], "2" => ["310", "311", "312", "313", "0"], "3" => ["15", "56", "16", "14", "73"], "4" => ["116", "145", "54", "379", "58"], "5" => ["52", "121", "17", "46", "47"]];
	public $baus = ["§e§l" => 1, "§aBaú 2" => 2, "§aBaú 3" => 3, "§aBaú 4" => 4, "§aBaú 5" => 5];
	public $bb = [];
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
         @mkdir($this->getDataFolder());
		$this->data = new Config($this->getDataFolder() . "data.json", Config::JSON);
		$this->option = new Config($this->getDataFolder() . "options.yml", Config::YAML);
		$this->cash = $this->getServer()->getPluginManager()->getPlugin("Cash");
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function getOption(){
		return $this->option;
	}
	
	public function getCash(){
		return $this->cash;
	}
	
	public function onJoin(PlayerJoinEvent $e){
		$p = $e->getPlayer();
		$name = $p->getName();
		$data = $this->getData();
		$option = $this->getOption();
		if(!$data->exists($name)){
			$data->set($name, ["bau1" => []]);
			$data->save();
			$data->reload();
		}
		if(!$option->exists($name)){
			$option->set($name, [1 => ["size" => "27", "icone" => "54", "buy" => "on"], 2 => ["size" => "27", "icone" => "54", "buy" => "off"], 3 => ["size" => "27", "icone" => "54", "buy" => "off"], 4 => ["size" => "27", "icone" => "54", "buy" => "off"], 5 => ["size" => "27", "icone" => "54", "buy" => "off"]]);
			$option->save();
			$option->reload();
		}
	}
	
	public function onQuit(PlayerQuitEvent $e){
		$p = $e->getPlayer();
		$name = $p->getName();
		if(isset($this->chest[$name])){
			unset($this->chest[$name]);
			unset($this->bau[$name]);
		}
		if(isset($this->inv[$name])){
			unset($this->inv[$name]);
		}
		if(isset($this->icone[$name])){
			unset($this->icone[$name]);
		}
		if(isset($this->open[$name])){
			unset($this->open[$name]);
		}
		if(isset($this->aum[$name])){
			unset($this->aum[$name]);
		}
		if(isset($this->bb[$name])){
			unset($this->bb[$name]);
		}
		if(isset($this->all[$name])){
			unset($this->all[$name]);
		}
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if($sender instanceof Player){
			$name = $sender->getName();
			if(strtolower($cmd->getName()) == "bau"){
				if(isset($args[0])){
					if(is_numeric($args[0])){
						$count = $this->countBaus($name);
						if($args[0] <= $count){
							$size = $this->getBauSize($name, $args[0]);
							$inv = new ChestInv($sender, $size, "§aBaú " . $args[0]);
							$this->setItens($name, $inv, $args[0]);
							$sender->addWindow($inv);
							$this->chest[$name] = $inv;
							$this->open[$name] = true;
							$this->bau[$name] = $args[0];
							$this->getServer()->getScheduler()->scheduleRepeatingTask(new Update($this, $sender, $inv), 1);
						} else {
							$sender->sendMessage("§cErro você não possui o baú " . $args[0]);
						}
					} else {
						$sender->sendMessage("§cErro use: /bau { número }");
					}
				} else {
					$inv = new ChestInv($sender, 27, "§a•§e§lกระเป๋า§a•\n§bพกพา");
					$this->setMenu($inv, $sender);
					$sender->addWindow($inv);
					$this->inv[$name] = $inv;
					$this->all[$name] = "on";
				}
			}
		}
	}
	
	public function onTransaction(InventoryTransactionEvent $e){
		$transactions = $e->getTransaction()->getTransactions();
		$p = $e->getTransaction()->getPlayer();
		if(isset($this->chest[$p->getName()])){
			return true;
		}
		if(isset($this->all[$p->getName()]) and $this->all[$p->getName()] == "off"){
			$e->setCancelled();
		}
		$name = $p->getName();
		foreach($transactions as $action){
			$item = $action->getTargetItem();
			$custom = $item->getCustomName();
			if(isset($this->icone[$name])){
				$e->setCancelled();
				$inv = $this->icone[$name];
				if($custom == "§7Proximo"){
					if($this->pag[$name] < 5){
						$this->pag[$name]++;
					}
					$this->sendPag($inv, $this->pag[$name]);
				}
				if($custom == "§7Anterior"){
					if($this->pag[$name] > 0){
						$this->pag[$name]--;
					}
					$this->sendPag($inv, $this->pag[$name]);
				}
				if($custom == "§eโลโก้ กระเป๋า"){
					$id = $item->getId();
					$this->setIcone($name, $id, $this->bb[$name]);
					$inv->onClose($p);
					$p->sendMessage("§aเลือก§eโลโก้§aสำเร็จ");
					unset($this->icone[$name]);
				}
				if(isset($this->baus[$custom])){
					$num = $this->baus[$custom];
					$inv->onClose($p);
					unset($this->icone[$name]);
					$this->bb[$name] = $num;
					$this->addBau($p, "§eโลโก้ กระเป๋า");
				}
			}
			if(isset($this->inv[$name])){
				$e->setCancelled();
				$inv = $this->inv[$name];
				if($custom == "§a§lต้องปลดล็อค\n§e40 §aWallet"){
					if($this->hasBauDisponivel($name)){
						$num = $this->getBauDisponivel($name);
						$this->setObj("compra", $inv, 54, $num);
					} else {
						$inv->onClose($p);
						unset($this->inv[$name]);
						$p->sendMessage("\n§cVocê ja compro todos baus disponíveis para comprar!\n");
					}
				}
				if($custom == "§aAceitar"){
					$cash = $this->getCash();
					if($cash->myCash($p) < 9){
						$inv->onClose($p);
						$p->sendMessage("\n§cCash Insuficiente, você precisa de 12 cash para adquirir um baú\n");
						return true;
					}
					$cash->removeCash($p, 9);
					$num = $this->getBauDisponivel($name);
					$this->addBauBuy($name, $num);
					$inv->onClose($p);
					$p->sendMessage("\n§aBaú $num adquirido com Sucesso\n");
				}
				if($custom == "§e[§c§lย้อนกลับ§e]"){
					$inv->clearAll();
					$this->setMenu($inv, $p);
				}
				if($custom == "§eAumentar Baú"){
					$inv->onClose($p);
					unset($this->inv[$name]);
					if($this->hasSizeDisponivel($name)){
						$this->addBau($p, "aumentar");
					} else {
						$p->sendMessage("\n§cVocê não tem baú disponível para aumentar!\n");
					}
				}
				if($custom == "§eMudar ícone do Baú"){
					usleep(50000);
					$inv->onClose($p);
					$this->addBau($p, "mudar");
					unset($this->inv[$name]);
				}
				if(isset($this->baus[$custom])){
					$this->all[$name] = "bau";
					usleep(50000);
					$inv->onClose($p);
					$num = $this->baus[$custom];
					$this->addBau($p, $num);
					unset($this->inv[$name]);
				}
			}
			if(isset($this->aum[$name])){
				$e->setCancelled();
				$inv = $this->aum[$name];
				if(isset($this->baus[$custom])){
					$num = $this->baus[$custom];
					$id = $this->getIcone($name, $num);
					$this->setObj("compra", $inv, $id, $num);
					$this->bb[$name] = $num;
				}
				if($custom == "§aAceitar"){
					$cash = $this->getCash();
					if($cash->myCash($p) < 9){
						$inv->onClose($p);
						$p->sendMessage("\n§cCash Insuficiente, você precisa de 9 cash\n");
						return true;
					}
					$cash->removeCash($p, 9);
					$num = $this->bb[$name];
					$this->setSize($name, 54, $num);
					$inv->onClose($p);
					$p->sendMessage("\n§aSeu Baú $num foi aumentado com Sucesso\n");
					unset($this->bb[$name]);
				}
				if($custom == "§cNegar"){
					$inv->clearAll();
					$count = $this->countBaus($name);
					for($i = 1; $i <= $count; $i++){
						if($this->isSizeDisponivel($name, $i)){
							$id = $this->getIcone($name, $i);
							$item = Item::get($id, 0, 1)->setCustomName("§aBaú $i");
							$inv->setItem($i, $item);
						}
					}
				}
			}
		}
	}
	
	public function onClose(InventoryCloseEvent $e){
		$p = $e->getPlayer();
		$name = $p->getName();
		if(isset($this->inv[$name])){
			$this->getServer()->getScheduler()->scheduleRepeatingTask(new Close($this, $p, "inv"), 40);
		}
		if(isset($this->icone[$name])){
			unset($this->icone[$name]);
			//$this->getServer()->getScheduler()->scheduleRepeatingTask(new Close($this, $p, "icone"), 20);
		}
		if(isset($this->open[$name])){
			$inv = $this->chest[$name];
			$this->saveItens($inv->getContents(), $name, $this->bau[$name]);
			unset($this->open[$name]);
		}
		if(isset($this->aum[$name])){
			$this->getServer()->getScheduler()->scheduleRepeatingTask(new Close($this, $p, "aum"), 40);
		}
		if(isset($this->all[$name])){
			if($this->all[$name] == "bau"){
				$this->getServer()->getScheduler()->scheduleRepeatingTask(new Close($this, $p, "all"), 30);
				return true;
			}
			$this->getServer()->getScheduler()->scheduleRepeatingTask(new Close($this, $p, "all"), 85);
		}
	}
	
	public function addBau($p, $opt){
		if(is_numeric($opt)){
			$name = $p->getName();
			$size = $this->getBauSize($name, $opt);
			$inv = new ChestInv($p, $size, "§aBaú $opt");
			$this->setItens($name, $inv, $opt);
			$this->bau[$name] = $opt;
			$this->updateInv($inv, $p, "chest");
			return true;
		}
		if($opt == "mudar"){
			$inv = new ChestInv($p, 27, "§7Selecione um Baú");
			$name = $p->getName();
			$count = $this->countBaus($name);
			for($i = 1; $i <= $count; $i++){
				$id = $this->getIcone($name, $i);
				$item = Item::get($id, 0, 1)->setCustomName("§aBaú $i");
				$inv->setItem($i, $item);
			}
			$this->updateInv($inv, $p, $opt);
			$this->all[$name] = "off";
		}
		if($opt == "aumentar"){
			$inv = new ChestInv($p, 27, "§7Aumentar Baú");
			$name = $p->getName();
			$count = $this->countBaus($name);
			for($i = 1; $i <= $count; $i++){
				if($this->isSizeDisponivel($name, $i)){
					$id = $this->getIcone($name, $i);
					$item = Item::get($id, 0, 1)->setCustomName("§aBaú $i");
					$inv->setItem($i, $item);
				}
			}
			$this->updateInv($inv, $p, $opt);
			$this->all[$name] = "off";
		}
		if($opt == "icone"){
			$inv = new ChestInv($p, 27, "§7Selecione um ícone");
			$this->updateInv($inv, $p, "mudar");
			$this->icone[$p->getName()] = $inv;
			$this->pag[$p->getName()] = 1;
			$this->sendPag($inv, 1);
			$this->all[$name] = "off";
		}
	}
	
	//ok
	public function sendPag($inv, $num){
		$pag = $this->icones["$num"];
		$ids = [];
		foreach($pag as $id){
			$ids[] = Item::get($id, 0, 1)->setCustomName("§aícone");
		}
		$inv->setItem(1, $ids[0]);
		$inv->setItem(2, $ids[1]);
		$inv->setItem(3, $ids[2]);
		$inv->setItem(4, $ids[3]);
		$inv->setItem(5, $ids[4]);
		$inv->setItem(26, Item::get(339, 0, 1)->setCustomName("§7Proximo"));
		$inv->setItem(25, Item::get(131, 0, 1));
		$inv->setItem(24, Item::get(339, 0, 1)->setCustomName("§7Anterior"));
	}
	
	//ok
	public function updateInv($inv, $p, $opt = null){
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new Delayed($this, $p, $inv, $opt), 30);
	}
	
	//ok
	public function getBauSize($name, $num){
		if($name instanceof Player){
			$name = $name->getName();
		}
		$option = $this->getOption();
		$dt = $option->get($name);
		return $dt[$num]["size"];
	}
	
	public function setObj($obj, $inv, $id = 54, $n = ""){
		$inv->clearAll();
		if($obj == "compra"){
			$bau = Item::get($id, 0, 1)->setCustomName("§aBaú $n §r");
			$inv->setItem(1, $bau);
			$inv->setItem(3, Item::get(35, 5, 1)->setCustomName("§aAceitar"));
			$inv->setItem(4, Item::get(131, 0, 1));
			$inv->setItem(5, Item::get(35, 14, 1)->setCustomName("§cNegar"));
		}
	}
	
	//ok
	public function setIcone($name, $id, $bau){
		$option = $this->getOption();
		$config = $option->get($name);
		$config["$bau"]["icone"] = $id;
		$option->set($name, $config);
		$option->save();
		$option->reload();
	}
	
	public function setSize($name, $size, $bau){

		$option = $this->getOption();
		$config = $option->get($name);
		$config["$bau"]["size"] = $size;
		$option->set($name, $config);
		$option->save();
		$option->reload();
	}
	
	public function addBauBuy($name, $bau){
		$option = $this->getOption();
		$config = $option->get($name);
		$config["$bau"]["buy"] = "on";
		$option->set($name, $config);
		$option->save();
		$option->reload();
		$data = $this->getData();
		$config = $data->get($name);
		$bb = "bau" . $bau;
		$config[$bb] = [];
		$data->set($name, $config);
		$data->save();
		$data->reload();
	}
	
	public function hasBauDisponivel($name){
		$opt = $this->getOption();
		foreach($opt->get($name) as $bau => $config){
			if($config["buy"] == "off"){
				return true;
			}
		}
		return false;
	}
	
	public function hasSizeDisponivel($name){
		$opt = $this->getOption();
		foreach($opt->get($name) as $bau => $config){
			if($config["buy"] == "on" and $config["size"] != 54){
				return true;
			}
		}
		return false;
	}
	
	public function isSizeDisponivel($name, $num){
		$opt = $this->getOption()->get($name);
		if($opt[$num]["size"] !== 54){
			return true;
		}
		return false;
	}
	
	public function getBauDisponivel($name){
		$opt = $this->getOption();
		foreach($opt->get($name) as $bau => $config){
			if($config["buy"] == "off"){
				return $bau;
			}
		}
		return 0;
	}
	
	//ok
	public function countBaus($name){
		$opt = $this->getOption();
		if(!$opt->exists($name)){
			return 1;
		}
		$num = 0;
		foreach($opt->get($name) as $bau => $config){
			if($config["buy"] == "on"){
				$num++;
			}
		}
		return $num;
	}
	
	public function getIcone($name, $num){
		$opt = $this->getOption();
		foreach($opt->get($name) as $bau => $config){
			if($bau == "$num"){
				return $config["icone"];
			}
		}
	}
	
	public function setMenu($inv, $sender){
		$name = $sender->getName();
		$count = $this->countBaus($name);
		for($i = 1; $i <= $count; $i++){
			$id = $this->getIcone($name, $i);
			$item = Item::get($id, 0, 1)->setCustomName("§aBaú $i");
			$inv->setItem($i, $item);
		}
		$inv->setItem(24, Item::get(340, 0, 1)->setCustomName("§eComprar Baú"));
		$inv->setItem(26, Item::get(421, 0, 1)->setCustomName("§eMudar ícone do Baú"));
		$inv->setItem(25, Item::get(131, 0, 1)->setCustomName("§eAumentar Baú"));
	}
	
	public function setItens($name, $inv, $num){
		$data = $this->getData()->get($name);
		foreach($data as $bau => $itens){
			$bb = "bau" . $num;
			if($bau == $bb){
				if($itens !== null){
					foreach($itens as $iten => $enchs){
						$arr = explode(":", $iten);
						$slot = $arr[0];
						$id = $arr[1];
						$dmg = $arr[2];
						$count = $arr[3];
						$item = Item::get($id, $dmg, $count);
						if($enchs !== null){
							if(isset($enchs["custom"])){
								$item->setCustomName($enchs["custom"]);
							}
							if(isset($enchs["enchants"])){
								foreach($enchs["enchants"] as $n => $ench){
									$ar = explode(":", $ench);
									$idd = $ar[0];
									$lvl = $ar[1];
									$ech = Enchantment::getEnchantment($idd);
									$ech->setLevel($lvl);
									$item->addEnchantment($ech);
								}
							}
						}
						$inv->setItem($slot, $item);
					}
				}
			}
		}
	}
	
	public function removeBau($bau, $name){
		$data = $this->getData();
		$config = $data->get($name);
		$config[$bau] = [];
		$data->remove($name);
		$data->set($name, $config);
		$data->save();
		$data->reload();
	}
	
	public function addBauItens($bau, $name, $itens){
		$data = $this->getData();
		$config = $data->get($name);
		$config[$bau] = $itens;
		$data->set($name, $config);
		$data->save();
		$data->reload();
	}
	
	public function existsBau($bau, $name){
		$data = $this->getData()->get($name);
		return isset($data[$bau]);
	}
	
	public function saveItens($itens, $name, $num){
		$data = $this->getData();
		$bau = "bau" . $num;
		$inv = [];
		if($this->existsBau($bau, $name)){
			$this->removeBau($bau, $name);
		}
		foreach($itens as $slot => $item){
			$id = $item->getId();
			$dmg = $item->getDamage();
			$count = $item->getCount();
			$enchs = [];
			if($item->hasEnchantments()){
				foreach($item->getEnchantments() as $ench){
					$idd = $ench->getId();
					$lvl = $ench->getLevel();
					$enchs["enchants"][] = "$idd:$lvl";
				}
			}
			if($item->hasCustomName()){
				$custom = $item->getCustomName();
				$enchs["custom"] = "$custom"; 
			}
			$inv["$slot:$id:$dmg:$count"] = $enchs;
		}
		$this->addBauItens($bau, $name, $inv);
	}
}