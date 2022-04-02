<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPAHere extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpahere", "Request a player to teleport to your position", "<player>", false);
        $this->setPermission("essentials.tpahere");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        if($player->getName() === $sender->getName()){
            $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport to yourself");
            return false;
        }
        $this->getAPI()->requestTPHere($sender, $player);
        $player->sendMessage(TextFormat::AQUA . $sender->getName() . TextFormat::GREEN . "§e§lมีบุคคลต้องการส่งคำขอถึงคุณ\n §f/tpaccept §eเพื่อยอมรับคำขอ\n §f/tpdeny §e§lเพื่อปฏิเสธคำเชิญ");
        $sender->sendMessage(TextFormat::GREEN . "Teleport request sent to " . $player->getDisplayName(). "!");
        return true;
    }
} 