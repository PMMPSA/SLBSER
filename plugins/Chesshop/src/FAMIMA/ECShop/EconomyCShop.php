<?php

namespace FAMIMA\ECShop;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;

use FAMIMA\ECShop\EventListener;
use FAMIMA\ECShop\DatabaseManager;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;


class EconomyCShop extends PluginBase
{
	private $db;

	public $server;

	public function onEnable()
	{
		$plugin = "EconomyCShop";
		$logger = $this->getLogger();
		$logger->info(TF::GREEN.$plugin."ทำงาน");
		$logger->info(TF::AQUA.$plugin."การทำงานจะตั้งค่าใน config");
		$this->server = $this->getServer();
		new EventListener($this);
		$dir = $this->getDataFolder();
		@mkdir($dir, 0755);
		$this->db = new DatabaseManager($dir."ECShopPos.sqlite3");

		$logger->info(TF::BLUE."จะได้เปิดใช้งาน EventListener และ DatabaseManager");
		$logger->info(TF::BLUE."โหลด EconomyAPI....");
		if(($this->economy = $this->server->getPluginManager()->getPlugin("EconomyAPI")) === null)
		{
			$logger->alert("EconomyAPI ไม่ได้อยู่ !, กรุณาแนะนำ EconomyAPI");
			$this->server->getPluginManager()->disablePlugin($this);
		}

		$config = new Config($dir."Message.yml", Config::YAML, 
			[
			"Message1" => TF::GREEN."§eตั้งร้านขายของเรียบร้อย",
			"Message2" => TF::RED."§cไม่สามารถตั้งร้านได้ ตรวจพอหา §eChest §cไม่พบ",
			"Message3" => TF::RED."§eนี้คือร้านค้าของคุณ",
			"Message4" => TF::RED."§cคุณไม่สามารถเพิ่มสิ้นค้าลงได้",
			"Message5" => TF::RED."§bของในร้านค้าหมดแล้ว หากอยากได้สินค้าติดต่อผู้ขายมาลงสินค้า",
			"Message6" => TF::RED."§eทอง §aของผู้เล่นไม่พอ ไมาสามารถซื้อของได้",
			"Message7" => TF::RED."§eคุณไม่สามารถไปเปิดกล่องร้านค้าของคนอื่นได้นะ",
			"Message8" => TF::GOLD.TF::GOLD."%item".TF::GREEN."ไอเทม".TF::AQUA."%amount"."จำนวน".TF::GREEN."ซื้อของ",
			"Message9" => TF::BLUE."§b%item §aตองการซื้อไอเทมนี้มั้ย§e(%price §aทอง)",
			"Message10" => TF::RED."§eคุณไม่สามรถทุบทำลายร้านค้าคนอื่นได้นะ",
			"Message11" => TF::RED."§eคุณไม่สามรถทุบทำลายร้านค้าได้",
			"Message12" => TF::RED."§eร้านค้าถูกปิดลง"
			]);
		$this->message = $config->getAll();
		//var_dump($this->message);
	}

	public function MessageReplace(string $str, array $serrep)
	{
		foreach($serrep as $search => $replace)
		{
			$str = str_replace($search, $replace, $str);
		}
		return $str;
	}

	public function getMessage(string $message, $serrep = [])
	{
		return $this->MessageReplace( (isset($this->message[$message])) ? $this->message[$message] : TF::RED."ข้อผิดพลาด! ไม่มีข้อความที่มีอยู่", $serrep);
	}

	public function createChestShop($cpos, $spos, $owner, $item, $price)
	{
		$this->db->createChestShop($cpos->x, $cpos->y, $cpos->z, $spos->x, $spos->y, $spos->z,
		$owner, $item->getID(), $item->getDamage(), $item->getCount(), $price, $spos->getLevel()->getName());
	}

	public function isShopExists($pos)
	{
		return $this->db->isShopExists($pos->x, $pos->y, $pos->z, $pos->level->getName());
	}

	public function isShopChestExists($pos)
	{
		return $this->db->isShopChestExists($pos->x, $pos->y, $pos->z, $pos->level->getName());
	}

	public function getShopData($pos)
	{
		return $this->db->getShopData($pos->x, $pos->y, $pos->z, $pos->level->getName());
	}

	public function isExistsChests($pos)
	{
		$l = $pos->level;
		$existsdata = false;
		$cpos = [$pos->add(1), $pos->add(-1), $pos->add(0, 0, 1), $pos->add(0, 0, -1)];
		foreach ($cpos as $vector) {
			if($l->getBlock($vector)->getID() === 54)
			{
				$existsdata = true;
			}
		}
		return $existsdata;
	}

	public function getChests($pos)
	{
		$l = $pos->level;
		$posdata = false;
		$cpos = [$pos->add(1), $pos->add(-1), $pos->add(0, 0, 1), $pos->add(0, 0, -1)];
		foreach ($cpos as $vector) {
			if($l->getBlock($vector)->getID() === 54)
			{
				$posdata = $vector;
			}
		}
		return $posdata;
	}

	public function isExistChestInItem($pos, $item)
	{
		return $pos->level->getTile($pos)->getInventory()->contains($item);
	}

	public function removeChestInItem($pos, $item)
	{
		$pos->level->getTile($pos)->getInventory()->removeItem($item);
	}

	public function removeShop($pos)
	{
		$this->db->deleteShop($pos->x, $pos->y, $pos->z, $pos->level->getName());
	}

	public function onBuy($owner, $target, $amount)
	{
		$tmoney = $this->economy->myMoney($target);
		if($tmoney < $amount)
		{
			return false;
		}else{
			$this->economy->reduceMoney($target, $amount);
			$this->economy->addMoney($owner, $amount);
			return true;
		}
	}
}
