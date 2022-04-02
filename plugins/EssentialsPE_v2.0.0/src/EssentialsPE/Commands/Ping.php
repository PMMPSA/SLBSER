<?php
namespace EssentialsPE\Commands;


use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class Ping extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "ping", "Pong!");
        $this->setPermission("essentials.ping");
    }
    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        $sender->sendMessage("§b§lIP§f:§amonp.ddns.net\n§bPORT§f:§a19132");
        return true;
    }
}