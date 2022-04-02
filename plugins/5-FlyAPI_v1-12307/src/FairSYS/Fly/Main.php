<?php

namespace FairSYS\Fly;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Effect;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class Main extends PluginBase{
  
  public function onEnable(){
    $this->getLogger()->notice("§aPlugin Fly v1.0 ativado com sucesso!");
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
    switch($cmd->getName()){
      case "fly":
        if($sender->hasPermission("fly.cmd")){
          if(!isset($args[0])){
            $sender->sendMessage("§l§eคำสัง บิน\n§f/fly on §eเพื่อทำการบิน\n§f/fly off §6เพื่อทำการ ปิดบิน");
            return true;
            }
            switch($args[0]){
              
              case "on":
                $sender->setAllowFlight(TRUE);
                $sender->sendTitle("§b§lเปีดบิน");
                return true;
              case "off":
                $sender->setAllowFlight(FALSE);
                $sender->sendTitle("§c§lปิดบิน");
}
}
}
}
}