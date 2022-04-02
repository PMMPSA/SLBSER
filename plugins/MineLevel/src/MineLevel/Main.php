<?php

namespace MineLevel;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\entity\Effect;
use pocketmine\network\protocol\SetTitlePacket;

class Main extends PluginBase implements Listener{
    
    public $data;
    private $config;
    
    public function onEnable(){
        if(!file_exists($this->getDataFolder() . "data/")){
            @mkdir($this->getDataFolder() . "data/");
        }
        
        $this->data = new Config($this->getDataFolder() . "data/" . "data.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->config->save();
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    
    public function onJoin(PlayerJoinEvent $ev){
        $p = $ev->getPlayer()->getName();
        if(!($this->data->exists(strtolower($p)))){
            $this->data->set(strtolower($p), [0,50,1]);
            $this->data->save();
            return true;
        }
    }
    
    public function onBreak(BlockBreakEvent $ev){
        $block = $ev->getBlock();
        $p = $ev->getPlayer();
        if($block->getId() === Block::COBBLESTONE or $block->getId() === Block::EMERALD_ORE or $block->getId() === Block::REDSTONE_ORE or $block->getId() === Block::GOLD_ORE or $block->getId() === Block::IRON_ORE or $block->getId() === Block::LAPIS_ORE or $block->getId() === Block::LAPIS_BLOCK or $block->getId() === Block::IRON_BLOCK or $block->getId() === Block::DIAMOND_BLOCK or $block->getId() === Block::GOLD_BLOCK or $block->getId() === Block::COAL_ORE or $block->getId() === Block::COAL_BLOCK){
            $n = $this->data->get(strtolower($p->getName()));
            $name = strtolower($p->getName());
            $n[0] = $this->getCurrentExp($p) + 1;
            $this->data->set(strtolower($p->getName()), $n);
            $this->data->save();
            $p->sendTitle("". "§b•§e " . $this->data->get($name)[0] . " §c/§e " . $this->data->get($name)[1] . " §b•\n\n\n\n");
            if($this->getCurrentExp($p) >= $this->getLevelUpExp($p)){
                $n[0] = 0;
                $n[1] = $this->getNextLevelUpExp($p);
                $n[2] = $this->getNextLevel($p);
   //             $nchim = 1;
                $this->data->set(strtolower($p->getName()), $n);
                $this->data->save();
				$p->sendMessage("§c§l-§6=§e{ §aน§fั§aก§fข§aุ§fด §e}§6=§c-");
                $p->sendMessage("§eL§6v§f.§a" . $this->getCurrentLevel($p). "");//xin config :v :v
				$this->getServer()->broadcastMessage("§aน§fั§aก§fข§aุ§fด §c: §a" . $name . " §eอ§fั§eป§fเ§eว§fล§eใ§fน§eก§fา§eร§fข§eุ§fด §bเป็น §eL§6v§f.§a" .$this->getCurrentLevel($p) . " §l§e" .$name. " §6สุดยอดไปเลย");
                $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "effect ".$name." 3 500 15");
				$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$name." 1000");
				$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "xp 999 ".$name."");
				}
        }
        
    }
    
    public function onChat(PlayerChatEvent $ev){
    }
    
    
/*    public function onHeld(PlayerItemHeldEvent $ev){
        
    }
    
    public function onTouch(PlayerInteractEvent $ev){
        
    } 
    */
    public function getNextLevel($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $lv = $this->data->get($player)[2] + 1;
        return $lv;
    }
    
    public function getLevelUpExp($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $e = $this->data->get($player)[1];
        return $e;
    }
    
    public function getCurrentLevel($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $lv = $this->data->get($player)[2];
        return $lv;
    }
    
    public function getCurrentExp($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $e = $this->data->get($player)[0];
        return $e;
    }
    
    public function getNextLevelUpExp($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $e = $this->data->get($player)[1];
        return $e + 10;
    }
    

}

?>