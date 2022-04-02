<?php
namespace DarkN3ss\AdvertisingKick;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener{
    
    private $plugin;

    public function __construct(AdvertisingKick $plugin){
            $this->plugin = $plugin;
    }
    
    private $webEndings = array(".net",".com",".co",".org",".info",".tk","sv1.cmine.net","sv1"); 
        
    /**
    * @param PlayerChatEvent $event
    *
    * @priority       NORMAL
    * @ignoreCanceled false
    */
    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $playername = $event->getPlayer()->getDisplayName();
        //----------------------------
        $parts = explode('.', $message);
        if(sizeof($parts) >= 4)
        {
            if (preg_match('/[0-9]+/', $parts[1]))
            {
                $event->setCancelled(true);
                $player->kick("Advertising");
                echo "========================[Advertising]: Kicked " . $playername . " For saying: ". $message . " ========================\n";
            }
        }
        //----------------------------
        foreach ($this->webEndings as $url) {
            if (strpos($message, $url) !== FALSE) 
            {
                $event->setCancelled(true);
                $player->kick("Advertising");
                echo "========================[Advertising]: Kicked " . $playername . " For saying: ". $message . " ========================\n";
            }
        }
        //----------------------------
        
    }
}