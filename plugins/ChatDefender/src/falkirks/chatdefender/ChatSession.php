<?php
namespace falkirks\chatdefender;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\level\sound\AnvilFallSound;

class ChatSession extends PluginTask{
    private $state;
    private $m;
    private $blockLength;
    /** @var  Player */
    private $player;
    public function bindToPlayer(Player $player){
        $this->player = $player;
        $this->blockLength = 1;
        $this->state = 0;
    }
    public function sendMessage($message){
        if($this->state == 2){
            $this->player->sendMessage("§c§l ไม่สามารถพิมพ์ได้");
            return false;
        }
        elseif($this->similarityCheck($message) || $this->rateCheck()){
            if($this->state == 1){
                $this->state = 2;
                $this->player->sendMessage("§l§cคุณถูกห้ามพิมพ์§e " . $this->blockLength * $this->getOwner()->getConfig()->get("baseblocklength") . " §cวินาที");
                $this->getOwner()->getServer()->getScheduler()->scheduleDelayedTask($this, 20*$this->getOwner()->getConfig()->get("baseblocklength")*$this->blockLength);
                $this->blockLength++;
                return false;
            }
            else{
                $this->state = 1;
                $this->player->sendMessage("§e§lคำที่คุณพิมพ์ สั่นเกินไป");
                $this->m = array($message, time());
                return false;
            }
        }
        else{
            $this->m = array($message, time());
            return true;
        }
    }
    public function onRun($tick){
        $this->state = 0;
        $this->player->sendMessage("§l§aคุณถูกปลดบล็อคพิมพ์ได้แล้ว!.");
    }
    public function similarityCheck($message){
        if($this->player->hasPermission("chatdefender.exempt.similar")) return false;
        return ((strlen($message) - similar_text($this->m[0],$message)) <= $this->getOwner()->getConfig()->get("similarity"));
    }
    public function rateCheck(){
        if($this->player->hasPermission("chatdefender.exempt.rate")) return false;
        return ((time() - $this->m[1]) <= $this->getOwner()->getConfig()->get("ratelimit"));
    }
    public function isBlocked(){
        return $this->state === 2;
    }
}