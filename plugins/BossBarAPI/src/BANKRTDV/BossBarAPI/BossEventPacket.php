<?php

namespace BANKRTDV\BossBarAPI;

use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info;

class BossEventPacket extends DataPacket {
	const NETWORK_ID = Info::BOSS_EVENT_PACKET;
	public $eid;
	public $state;

	public function decode() { }//We do not need to overload the server without use of this..

	public function encode() {
		$this->reset();
		$this->putEntityId($this->eid);
		$this->putUnsignedVarInt($this->state);
	}
}