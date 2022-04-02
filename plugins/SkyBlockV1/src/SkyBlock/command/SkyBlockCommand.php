<?php

namespace SkyBlock\command;

use SkyBlock\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use SkyBlock\invitation\Invitation;
use SkyBlock\island\Island;
use SkyBlock\Main;
use SkyBlock\reset\Reset;
use pocketmine\level\sound\NoteblockSound;

class SkyBlockCommand extends Command {

    /** @var Main */
    private $plugin;

    /**
     * SkyBlockCommand constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        parent::__construct("skyblock", "Main SkyBlock command", "Usage: /skyblock", ["sb"]);
    }

    public function sendMessage(Player $sender, $message) {
        $sender->sendMessage(TextFormat::GREEN . "- " . TextFormat::WHITE . $message);
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if($sender instanceof Player) {
            if(isset($args[0])) {
                switch($args[0]) {
                    case "join":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                $island->addPlayer($sender);
                                $sender->teleport(new Position(9, 35, 9, $this->plugin->getServer()->getLevelByName($island->getIdentifier())));
                                $this->sendMessage($sender, "§e§lวาปมาเกาะของคุณเเล้ว");
								$sender->getLevel()->addSound(new NoteblockSound($sender));
                            }
                            else {
                                $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                            }
                        }
                        break;
                    case "create":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $reset = $this->plugin->getResetHandler()->getResetTimer($sender);
                            if($reset instanceof Reset) {
                                $minutes = Utils::printSeconds($reset->getTime());
                                $this->sendMessage($sender, "§e§lคุณสามารถสร้างเกาะได้ในอีก§f {$minutes} §eนาที");
                            }
                            else {
                                $skyBlockManager = $this->plugin->getSkyBlockGeneratorManager();
                                if(isset($args[1])) {
                                    if($skyBlockManager->isGenerator($args[1])) {
                                        $this->plugin->getSkyBlockManager()->generateIsland($sender, $args[1]);
                                        $this->sendMessage($sender, "§You successfully created a {$skyBlockManager->getGeneratorIslandName($args[1])} island!");
                                    }
                                    else {
                                        $this->sendMessage($sender, "§cThat isn't a valid SkyBlock generator!");
                                    }
                                }
                                else {
                                    $this->plugin->getSkyBlockManager()->generateIsland($sender, "basic");
                                    $this->sendMessage($sender, "§a§lสร้างเกาะสำเร็จ §f/skyblock join §aเพื่อไปเกาะของคุณ");
									$sender->getLevel()->addSound(new NoteblockSound($sender));
                                }
                            }
                        }
                        else {
                            $this->sendMessage($sender, "§6§lไม่สามารถสร้างได้ คุณมีเกาะอยู่แล้ว");
                        }
                        break;
                    case "home":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                $home = $island->getHomePosition();
                                if($home instanceof Position) {
                                    $sender->teleport($home);
                                    $this->sendMessage($sender, "§e§lวาปมาเกาะขอฝคุณเเล้ว");
									$sender->getLevel()->addSound(new NoteblockSound($sender));
                                }
                                else {
                                    $this->sendMessage($sender, "§cใช้คำสังนี้ไม่ได้คุณยังไม่ได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                            }
                        }
                        break;
                    case "sethome":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    if($sender->getLevel()->getName() == $config->get("island")) {
                                        $island->setHomePosition($sender->getPosition());
                                        $this->sendMessage($sender, "§a§lคุณเซ็ตโฮมเกาะสำเร็จ");
                                    }
                                    else {
                                        $this->sendMessage($sender, "§cคุณต้องอยูเกาะของคุณก่อนถึงจะ sethome ได้");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณไม่ใช้เจ้าของเกาะไม่สามารถทำแบบนี้ได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                            }
                        }
                        break;
                    case "kick":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    if(isset($args[1])) {
                                        $player = $this->plugin->getServer()->getPlayer($args[1]);
                                        if($player instanceof Player and $player->isOnline()) {
                                            if($player->getLevel()->getName() == $island->getIdentifier()) {
                                                $player->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn());
                                                $this->sendMessage($sender, "§6{$player->getName()} §aเตะออกจากเกาะสำเร็จ");
                                            }
                                            else {
                                                $this->sendMessage($sender, "§l§cผู้เล่นนี้ไม่ได้อยู่เกาะของคุณ");
                                            }
                                        }
                                        else {
                                            $this->sendMessage($sender, "§cใส่ชื่อผู้เล่นผิด");
                                        }
                                    }
                                    else {
                                        $this->sendMessage($sender, "§aUsage: /skyblock kick <name>");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณไม่ใช้เจ้าของเกาะ ไม่สามารถเตะได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                            }
                        }
                        break;
                    case "lock":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    $island->setLocked(!$island->isLocked());
                                    $locked = ($island->isLocked()) ? "§clocked" : "unlocked";
                                    $this->sendMessage($sender, "§aคุณล็อคเกาะสำเร็จ {$locked}!");
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณไม่ใช้เจ้าของเกาะไม่สามารถทำได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                            }
                        }
                        break;
                    case "invite":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    if(isset($args[1])) {
                                        $player = $this->plugin->getServer()->getPlayer($args[1]);
                                        if($player instanceof Player and $player->isOnline()) {
                                            $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($player);
                                            if(empty($config->get("island"))) {
                                                $this->plugin->getInvitationHandler()->addInvitation($sender, $player, $island);
                                                $this->sendMessage($sender, "§aคุณได้ส่งคำเชิญเข้าร่วมเกาะ {$player->getName()}!");
                                                $this->sendMessage($player, "§6{$sender->getName()} §eได้เชิญคุณเข้าร่วมเกาะ พิม /skyblock <accept> ตามด้วยชื่อเจ้าของเกาะ {$sender->getName()}");
                                            }
                                            else {
                                                $this->sendMessage($sender, "§cมีเพื่อนคนนี้อยู่ในเกาะแล้ว!");
                                            }
                                        }
                                        else {
                                            $this->sendMessage($sender, "§6{$args[1]} §cไม่มีผู้เล่นนี้ในเซิฟ");
                                        }
                                    }
                                    else {
                                        $this->sendMessage($sender, "§aUsage: /skyblock invite <player>");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณไม่ใช้เจ้าของเกาะไม่สามารถทำได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§e§l• §6คุณยังไม่ได้สร้างเกาะ §f/skyblock create §6เพื่อทำการสร้างเกาะ");
                            }
                        }
                        break;
                    case "accept":
                        if(isset($args[1])) {
                            $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                            if(empty($config->get("island"))) {
                                $player = $this->plugin->getServer()->getPlayer($args[1]);
                                if($player instanceof Player and $player->isOnline()) {
                                    $invitation = $this->plugin->getInvitationHandler()->getInvitation($player);
                                    if($invitation instanceof Invitation) {
                                        if($invitation->getSender() == $player) {
                                            $invitation->accept();
                                        }
                                        else {
                                            $this->sendMessage($sender, "§6คุณไม่ได้รับเชิญเข้าเกาะ {$player->getName()}!");
                                        }
                                    }
                                    else {
                                        $this->sendMessage($sender, "§6คุณไม่ได้รับเชิญเข้าเกาะ {$player->getName()}");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§6{$args[1]} §cไม่มีผู้เล่นดังกล่าว");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cคุณมีเกาะอยู่แล้วไม่สามารับเข้าเกาะได้อีก");
                            }
                        }
                        else {
                            $this->sendMessage($sender, "§aUsage: /skyblock accept <sender name>");
                        }
                        break;
                    case "deny":
                    case "reject":
                        if(isset($args[1])) {
                            $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                            if(empty($config->get("island"))) {
                                $player = $this->plugin->getServer()->getPlayer($args[1]);
                                if($player instanceof Player and $player->isOnline()) {
                                    $invitation = $this->plugin->getInvitationHandler()->getInvitation($player);
                                    if($invitation instanceof Invitation) {
                                        if($invitation->getSender() == $player) {
                                            $invitation->deny();
                                        }
                                        else {
                                            $this->sendMessage($sender, "§6คุณยังไม่ได้รับคำเชิญเขเาเกาะ {$player->getName()}!");
                                        }
                                    }
                                    else {
                                        $this->sendMessage($sender, "§6คุณยังไม่ได้รับคำเชิญเข้าเกาะ {$player->getName()}");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§6{$args[1]} §cไม่พบผู้เล่นดังกล่าว");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cคุณไม่ต้องยกเลิกขอเข้าเกาะ เพราะคุณมีเกาะอยู่แล้ว");
                            }
                        }
                        else {
                            $this->sendMessage($sender, "§aUsage: /skyblock accept <sender name>");
                        }
                        break;
					case "members":
                         $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                         if(empty($config->get("island"))) {
                             $this->sendMessage($sender, "§cคุณต้องอยู่เกาะก่อนถึงจะใช้คำสั่งนี้ได้");
                         }
                         else {
                             $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                             if($island instanceof Island) {
                                 $this->sendMessage($sender, "§c____§b| §6{$island->getOwnerName()} §a's Members §b|§c____");
                                 $i = 1;
                                foreach($island->getAllMembers() as $member) {
                                     $this->sendMessage($sender, "{$i}. {$member}");
                                     $i++;
                                 }
                             }
                             else {
                                 $this->sendMessage($sender, "§cYou must be in a island to use this command!!");
                             }
                         }
                         break;
                    case "disband":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§cคุณต้องอยู่เกาะของคุณถึงจะ disband ได้");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    foreach($island->getAllMembers() as $member) {
                                        $memberConfig = new Config($this->plugin->getDataFolder() . "users" . DIRECTORY_SEPARATOR . $member . ".json", Config::JSON);
                                        $memberConfig->set("island", "");
                                        $memberConfig->save();
                                    }
                                    $this->plugin->getIslandManager()->removeIsland($island);
                                    $this->plugin->getResetHandler()->addResetTimer($sender);
                                    $this->sendMessage($sender, "§aทำการลบเกาะของคุณสำเร็จ");
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณไม่ใช้เจ้าของเกาะไม่สามารถลบเกาะได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cคุณต้องอยู่เกาะของคุณถึงจะ disband ได้");
                            }
                        }
                        break;
                    case "makeleader":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§cคุณต้องมีเกาะถึงจะให้เกาะได้");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    if(isset($args[1])) {
                                        $player = $this->plugin->getServer()->getPlayer($args[1]);
                                        if($player instanceof Player and $player->isOnline()) {
                                            $playerConfig = $this->plugin->getSkyBlockManager()->getPlayerConfig($player);
                                            $playerIsland = $this->plugin->getIslandManager()->getOnlineIsland($playerConfig->get("island"));
                                            if($island == $playerIsland) {
                                                $island->setOwnerName($player);
                                                $island->addPlayer($player);
                                                $this->sendMessage($sender, "§aคุณได้โอนย้ายเจ้าของเกาะให้ {$player->getName()}");
                                                $this->sendMessage($player, "คุณถูกแต่งตั้งให้เป็นเจ้าของเกาะโดย {$sender->getName()}");
                                            }
                                            else {
                                                $this->sendMessage($sender, "§cไม่สามารถย้ายได้ผู้เล่นคนนี้ยังไม่ได้เข้าร่วมเกาะของคุณ");
                                            }
                                        }
                                        else {
                                            $this->sendMessage($sender, "§6{$args[1]} §cไม่พบผู้เล่น");
                                        }
                                    }
                                    else {
                                        $this->sendMessage($sender, "§aUsage: /skyblock makeleader <player>");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณต้องเป็นเจ้าของเกาะถึงจะทำได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cคุณต้องมีเกาะก่อนถึงจะทำได้");
                            }
                        }
                        break;
                    case "leave":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§cคุณต้องอยู่เกาะถึงจะทำได้");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    $this->sendMessage($sender, "§cคุณเป็นเจ้าของเกาะไม่สามารถออกได้ หรือคุณต้องใช้คำสั่งลบเกาะเพื่อออก /skyblock disband");
                                }
                                else {
                                    $this->plugin->getChatHandler()->removePlayerFromChat($sender);
                                    $config->set("island", "");
                                    $config->save();
                                    $island->removeMember(strtolower($sender->getName()));
                                    $this->sendMessage($sender, "§cคุณได้ออกจากเกาะ");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cคุณต้องอยู่เกาะถึงจะทำได้");
                            }
                        }
                        break;
                    case "remove":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§cคุณต้องมีเกาะก่อนถึงจะลบสมาชิกได้");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    if(isset($args[1])) {
                                        if(in_array(strtolower($args[1]), $island->getMembers())) {
                                            $island->removeMember(strtolower($args[1]));
                                            $player = $this->plugin->getServer()->getPlayerExact($args[1]);
                                            if($player instanceof Player and $player->isOnline()) {
                                                $this->plugin->getChatHandler()->removePlayerFromChat($player);
                                            }
                                            $this->sendMessage($sender, "§6{$args[1]} ถูกลบออกจากเกาะ");
                                        }
                                        else {
                                            $this->sendMessage($sender, "§6{$args[1]} ผู้เล่นดังกล่าวไม่ได้อยู่เกาะของคุณ");
                                        }
                                    }
                                    else {
                                        $this->sendMessage($sender, "§aUsage: /skyblock remove <player>");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณต้องเป็นเจ้าของเกาะจะทำเช่นนี้!");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cคุณต้องมีเกาะก่อนถึงจะทำได้");
                            }
                        }
                        break;
                    case "tp":
                        if(isset($args[1])) {
                            $island = $this->plugin->getIslandManager()->getIslandByOwner($args[1]);
                            if($island instanceof Island) {
                                if($island->isLocked()) {
                                    $this->sendMessage($sender, "§cเกาะนี้ถูก §6ล็อค §cไม่สามารถเข้าได้");
                                }
                                else {
                                    $sender->teleport(new Position(8, 35, 9, $this->plugin->getServer()->getLevelByName($island->getIdentifier())));
                                    $this->sendMessage($sender, "§aคุณเข้าเกาะสำเร็จ");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cไม่สามารถดูเกาะได้เพราะไม่มีสมาชิกเกาะออนไลน์อยู่เลย");
                            }
                        }
                        else {
                            $this->sendMessage($sender, "§aUsage: /skyblock tp <owner name>");
                        }
                        break;
                    case "reset":
                        $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                        if(empty($config->get("island"))) {
                            $this->sendMessage($sender, "§cคุณต้องมีเกาะเพื่อจะ รีเซ็ต");
                        }
                        else {
                            $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                            if($island instanceof Island) {
                                if($island->getOwnerName() == strtolower($sender->getName())) {
                                    $reset = $this->plugin->getResetHandler()->getResetTimer($sender);
                                    if($reset instanceof Reset) {
                                        $minutes = Utils::printSeconds($reset->getTime());
                                        $this->sendMessage($sender, "§cคุณจะรีเซ็ตเกาะได้อีกใน §6{$minutes} §eนาที");
                                    }
                                    else {
                                        foreach($island->getAllMembers() as $member) {
                                            $memberConfig = new Config($this->plugin->getDataFolder() . "users" . DIRECTORY_SEPARATOR . $member . ".json", Config::JSON);
                                            $memberConfig->set("island", "");
                                            $memberConfig->save();
                                        }
                                        $generator = $island->getGenerator();
                                        $this->plugin->getIslandManager()->removeIsland($island);
                                        $this->plugin->getResetHandler()->addResetTimer($sender);
                                        $this->plugin->getSkyBlockManager()->generateIsland($sender, $generator);
                                        $this->sendMessage($sender, "§aคุณ รีเซ็ตเกาะ สำเร็จ");
                                    }
                                }
                                else {
                                    $this->sendMessage($sender, "§cคุณไม่ใช้เจ้าของเกาะไม่สามารถ รีเซ็ตได้");
                                }
                            }
                            else {
                                $this->sendMessage($sender, "§cคุณต้องมีเกาะในการรีเซ็ต!");
                            }
                        }
                        break;
                    case "info":
                        $commands = [
                            "§6info" => "§c§l-§6=§e[ §bคำสัง §aSky§dBlock §e]§6=§c-",
                            "§6create" => "§eคำสั่งสำหรับสร้างเกาะ",
                            "§6join" => "§eคำสั่งเข้าเกาะของผู้เล่น",
                            "§6kick" => "§eคำสั่งเตะผู้เล่นออกจากเกาะ",
                            "§6remove" => "§eไล่สมาชิกในเกาะออก",
                            "§6lock" => "§eคำสั้งไม่ให้คนอื่นส่องเกาะ§f /skyblock unlock §eเปิดให้ผู้เล่นส่องเกาะ",
                            "§6sethome" => "§eเซ็ต home เกาะ",
                            "§6home" => "§eคำสั่งกลับเกาะ",
                            "§6members" => "§eคำสั่งดูสมาชิกในเกาะ",
                            "§6tp §f<§bชื่อเจ้าของเกาะ§f>" => "§eคำสั่งส่องเกาะของเพื่อน",
                            "§6invite" => "§eคำสั่งเชิญเพื่อนเข้าร่วมเกาะ",
                            "§6leave" => "§eออกจากเกาะ",
                            "§6disband" => "§eลบเกาะ",
                            "§6makeleader" => "§eโอนย้ายเจ้าของเกาะ",
                        ];
                        foreach($commands as $command => $description) {
                            $sender->sendMessage(TextFormat::GREEN . "§d/skyblock {$command}: " . TextFormat::AQUA . $description);
                        }
					case "turler":
					        $this->sendMessage($sender, "§a=====================");
                            $this->sendMessage($sender, "§e+++++++++++++++++++++");
                        break;
                    case "tsohbet":
                        if($this->plugin->getChatHandler()->isInChat($sender)) {
                            $this->plugin->getChatHandler()->removePlayerFromChat($sender);
                            $this->sendMessage($sender, "§cTakım sohbetinden ayrıldın!");
                        }
                        else {
                            $config = $this->plugin->getSkyBlockManager()->getPlayerConfig($sender);
                            if(empty($config->get("island"))) {
                                $this->sendMessage($sender, "§cKendine ada oluşturmalısın! §6/ada olustur §ckomutunu girerek ada oluşturabilirsin!");
                            }
                            else {
                                $island = $this->plugin->getIslandManager()->getOnlineIsland($config->get("island"));
                                if($island instanceof Island) {
                                    $this->plugin->getChatHandler()->addPlayerToChat($sender, $island);
                                    $this->sendMessage($sender, "§aTakım sohbetine katıldın!");
                                }
                                else {
                                    $this->sendMessage($sender, "§cYou must be in a island to use this command!!");
                                }
                            }
                        }
                        break;
                    default:
                        $this->sendMessage($sender, "§e§lคุณพิมคำสั่งผิด กรุณาพิม §f/skyblock info §eเพื่อเปิดดูการใช้คำสั่ง");
                        break;
                }
            }
            else {
                $this->sendMessage($sender, "§e§lคุณพิมคำสั่งผิด กรุณาพิม §f/skyblock info §eเพื่อเปิดดูการใช้คำสั่ง");
            }
        }
        else {
            $sender->sendMessage("§cPlease, run this command in game.");
        }
    }

}