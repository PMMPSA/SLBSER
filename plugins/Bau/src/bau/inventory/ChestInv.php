<?php

namespace bau\inventory;

use pocketmine\block\Block;
use pocketmine\inventory\CustomInventory;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\inventory\InventoryType;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\NBT;
use pocketmine\network\protocol\BlockEntityDataPacket;
use pocketmine\inventory\InventoryHolder;

class ChestInv extends CustomInventory{
	
	protected $customName = "";
	protected $tile;
	protected $block;
	private $double = false;
	private $update = false;
	
	public function __construct(Player $player, $size = 27, $name = "") {
		$this->tile = Tile::CHEST;
		$this->block = 54;
		$this->player = $player;
		$type = InventoryType::get(InventoryType::CHEST);
		switch($size){
			case 54:
			$this->tile = Tile::CHEST;
			$this->block = 54;
			$type = InventoryType::get(InventoryType::DOUBLE_CHEST);
			$this->double = true;
			break;
			case 27:
			$type = InventoryType::get(InventoryType::CHEST);
			$this->tile = Tile::CHEST;
			$this->block = 54;
			break;
		}
		$this->customName = $name;
		$holder = new WindowHolder($player->getFloorX(), $player->getFloorY() + 2, $player->getFloorZ(), $this);
		parent::__construct($holder, $type);
	}
	
	public function onOpen(Player $who){
		$this->holder = $holder = new WindowHolder($who->getFloorX(), $who->getFloorY() + 2, $who->getFloorZ(), $this);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x - 1;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->blockId = 0;
		$pk->blockData = 0;
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z + 1;
		$pk->blockId = 0;
		$pk->blockData = 0;
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z - 1;
		$pk->blockId = 0;
		$pk->blockData = 0;
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->blockId = $this->block;
		$pk->blockData = 0;
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$c = new CompoundTag("", [
		new StringTag("id", $this->tile),
		new IntTag("x", (int) $holder->x),
		new IntTag("y", (int) $holder->y),
		new IntTag("z", (int) $holder->z),
		new IntTag("pairx", (int) $holder->x + 1),
		new IntTag("pairz", (int) $holder->z),
		new StringTag("CustomName", $this->customName)
		]);
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData($c);
		$pk = new BlockEntityDataPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->namedtag = $nbt->write();
		$who->dataPacket($pk);
		if($this->double){
			$pk = new UpdateBlockPacket();
			$pk->x = $holder->x + 1;
			$pk->y = $holder->y;
			$pk->z = $holder->z;
			$pk->blockId = $this->block;
			$pk->blockData = 0;
			$pk->flags = UpdateBlockPacket::FLAG_ALL;
			$who->dataPacket($pk);
			$c = new CompoundTag("", [
			new StringTag("id", $this->tile),
			new IntTag("x", (int) $holder->x + 1),
			new IntTag("y", (int) $holder->y),
			new IntTag("z", (int) $holder->z),
			new StringTag("CustomName", $this->customName)
			]);
			$nbt = new NBT(NBT::LITTLE_ENDIAN);
			$nbt->setData($c);
			$pk = new BlockEntityDataPacket();
			$pk->x = $holder->x + 1;
			$pk->y = $holder->y;
			$pk->z = $holder->z;
			$pk->namedtag = $nbt->write();
			$who->dataPacket($pk);
		} else {
			$pk = new UpdateBlockPacket();
			$pk->x = $holder->x + 1;
			$pk->y = $holder->y;
			$pk->z = $holder->z;
			$pk->blockId = 0;
			$pk->blockData = 0;
			$pk->flags = UpdateBlockPacket::FLAG_ALL;
			$who->dataPacket($pk);
		}
		usleep(500000);
		parent::onOpen($who);
		$this->sendContents($who);
	}
	
	public function onClose(Player $who){
		$holder = $this->holder;
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x - 1;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->blockId = $who->getLevel()->getBlockIdAt($holder->x - 1, $holder->y, $holder->z);
		$pk->blockData = $who->getLevel()->getBlockDataAt($holder->x - 1, $holder->y, $holder->z);
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z + 1;
		$pk->blockId = $who->getLevel()->getBlockIdAt($holder->x, $holder->y, $holder->z + 1);
		$pk->blockData = $who->getLevel()->getBlockDataAt($holder->x, $holder->y, $holder->z + 1);
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z - 1;
		$pk->blockId = $who->getLevel()->getBlockIdAt($holder->x, $holder->y, $holder->z - 1);
		$pk->blockData = $who->getLevel()->getBlockDataAt($holder->x, $holder->y, $holder->z - 1);
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->blockId = $who->getLevel()->getBlockIdAt($holder->x, $holder->y, $holder->z);
		$pk->blockData = $who->getLevel()->getBlockDataAt($holder->x, $holder->y, $holder->z);
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		if($this->double){
			$pk = new UpdateBlockPacket();
			$pk->x = $holder->x + 1;
			$pk->y = $holder->y;
			$pk->z = $holder->z;
			$pk->blockId = $who->getLevel()->getBlockIdAt($holder->x + 1, $holder->y, $holder->z);
			$pk->blockData = $who->getLevel()->getBlockDataAt($holder->x + 1, $holder->y, $holder->z);
			$pk->flags = UpdateBlockPacket::FLAG_ALL;
			$who->dataPacket($pk);
		} else {
			$pk = new UpdateBlockPacket();
			$pk->x = $holder->x + 1;
			$pk->y = $holder->y;
			$pk->z = $holder->z;
			$pk->blockId = $who->getLevel()->getBlockIdAt($holder->x + 1, $holder->y, $holder->z);
			$pk->blockData = $who->getLevel()->getBlockDataAt($holder->x + 1, $holder->y, $holder->z);
			$pk->flags = UpdateBlockPacket::FLAG_ALL;
		}
		usleep(550000);
		parent::onClose($who);
	}
}