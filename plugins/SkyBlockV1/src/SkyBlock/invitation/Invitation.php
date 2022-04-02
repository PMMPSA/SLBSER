<?php

namespace SkyBlock\invitation;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SkyBlock\island\Island;

class Invitation {

    /** @var InvitationHandler */
    private $handler;

    /** @var Player */
    private $sender;

    /** @var Player */
    private $receiver;

    /** @var Island */
    private $island;

    /** @var int */
    private $time = 60;

    /**
     * Invitation constructor.
     *
     * @param InvitationHandler $handler
     * @param Player $sender
     * @param Player $receiver
     * @param Island $island
     */
    public function __construct(InvitationHandler $handler, Player $sender, Player $receiver, Island $island) {
        $this->handler = $handler;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->island = $island;
    }

    /**
     * Return invitation sender
     *
     * @return Player
     */
    public function getSender() {
        return $this->sender;
    }

    /**
     * Return invitation receiver
     *
     * @return Player
     */
    public function getReceiver() {
        return $this->receiver;
    }

    public function accept() {
        $config = $this->handler->getPlugin()->getSkyBlockManager()->getPlayerConfig($this->receiver);
        if(empty($config->get("island"))) {
            $config->set("island", $this->island->getIdentifier());
            $config->save();
            $this->island->addMember($this->receiver);
            $this->sender->sendMessage(TextFormat::RED . "§a✔§f " . TextFormat::YELLOW . "§e{$this->receiver->getName()}§f đã chấp nhận lời mời!");
            $this->receiver->sendMessage(TextFormat::RED . "§a✔§f " . TextFormat::YELLOW . "§fBạn đã tham gia đảo của §e{$this->sender->getName()}§f!");
        }
        else {
            $this->sender->sendMessage(TextFormat::RED . "§c➡§f " . TextFormat::YELLOW . "§e{$this->receiver->getName()}§f đã ở trên đảo!");
        }
        $this->handler->removeInvitation($this);
    }

    public function deny() {
        $this->sender->sendMessage(TextFormat::RED . "§a➡§f " . TextFormat::YELLOW . "§e{$this->receiver->getName()} đã từ chối lời mời!");
        $this->receiver->sendMessage(TextFormat::RED . "§a➡§f " . TextFormat::YELLOW . "Bạn đã từ chối lời mời của §e{$this->sender->getName()}§f!");
        $this->handler->removeInvitation($this);
    }

    public function expire() {
        $this->sender->sendMessage(TextFormat::RED . "§c➡§f " . TextFormat::YELLOW . "Lời mời gửi tới §e{$this->receiver->getName()}§f đã hết thời gian!");
        $this->handler->removeInvitation($this);
    }

    public function tick() {
        if($this->time <= 0) {
            $this->expire();
        }
        else {
            $this->time--;
            $this->sender->sendPopup(TextFormat::RED . "§a▶§f Lời mời gửi tới §e{$this->receiver->getName()}§f sẽ hết thời hạn vào §e{$this->time} giây§f nữa! §a◀");
        }
    }

}