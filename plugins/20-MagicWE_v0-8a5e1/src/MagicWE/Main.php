<?php namespace MagicWE;
 use pocketmine\Player;
 use pocketmine\command\Command;
 use pocketmine\utils\TextFormat;
 use pocketmine\plugin\PluginBase;
 use pocketmine\event\Listener;
 use pocketmine\command\CommandSender;
 use pocketmine\math\Vector3;
 use pocketmine\level\Level;
 use pocketmine\level\Position;
 use pocketmine\event\block\BlockBreakEvent;
 use pocketmine\item\Item;
 use pocketmine\event\player\PlayerInteractEvent;
 class Main extends PluginBase implements Listener{ public $areas;
 private $pos1 = [], $pos2 = [], $copy = [], $copypos = [], $undo = [], $redo = [], $wand = [], $schematics = [];
 private static $MAX_BUILD_HEIGHT = 128;
 public function onLoad(){ $this->getLogger()->info(TextFormat::GREEN . "MagicWE Foi carregado!");
 } public function onEnable(){ $this->saveResource("config.yml");
 $this->getServer()->getPluginManager()->registerEvents($this, $this);
 $this->getLogger()->info(TextFormat::GREEN . "MagicWE traduzido por §bLucasGamer §aativado!");
 } public function onCommand(CommandSender $sender, Command $command, $label, array $args){ if($sender instanceof Player){ switch($command){ case "/pos1": { if(!$sender->hasPermission("magicwe.command.pos1")) return;
 $pos1x = $sender->getFloorX();
 $pos1y = $sender->getFloorY();
 $pos1z = $sender->getFloorZ();
 $this->pos1[$sender->getName()] = new Vector3($pos1x, $pos1y, $pos1z);
 if($pos1y > self::$MAX_BUILD_HEIGHT || $pos1y < 0) $sender->sendMessage(TextFormat::GOLD . "§7[§aMWE§7] §cAviso§f: §7Você está acima §ay§f:" . self::$MAX_BUILD_HEIGHT . " §7ou abaixo §ay§f:0");
 $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Posição §f1 §7definada como §ax§f:" . $pos1x . " §ay§f:" . $pos1y . " §az§f:" . $pos1z);
 return true;
 break;
 } case "/pos2": { if(!$sender->hasPermission("magicwe.command.pos2")) return;
 $pos2x = $sender->getFloorX();
 $pos2y = $sender->getFloorY();
 $pos2z = $sender->getFloorZ();
 $this->pos2[$sender->getName()] = new Vector3($pos2x, $pos2y, $pos2z);
 if($pos2y > self::$MAX_BUILD_HEIGHT || $pos2y < 0) $sender->sendMessage(TextFormat::GOLD . "§7[§aMWE§7] §cAviso§f: §7Você está acima §ay§f:" . self::$MAX_BUILD_HEIGHT . " §7ou abaixo §ay§f:0");
 $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Posição §f2 §7definada como §ax§f:" . $pos2x . " §ay§f:" . $pos2y . " §az§f:" . $pos2z);
 return true;
 break;
 } case "/set": { if(!$sender->hasPermission("magicwe.command.set")) return;
 if(isset($args[0])){ if(isset($this->pos1[$sender->getName()], $this->pos2[$sender->getName()])){ $this->fill($sender, $args[0]);
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cArgumentos ausentes");
 } break;
 } case "/replace": { if(!$sender->hasPermission("magicwe.command.replace")) return;
 if(isset($args[0]) && isset($args[1])){ if(isset($this->pos1[$sender->getName()], $this->pos2[$sender->getName()])){ $this->replace($sender, $args[0], $args[1]);
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cArgumentos ausentes");
 } break;
 } case "/copy": { if(!$sender->hasPermission("magicwe.command.copy")) return;
 if(isset($this->pos1[$sender->getName()], $this->pos2[$sender->getName()])){ $this->copy($sender);
 return true;
 } break;
 } case "/paste": { if(!$sender->hasPermission("magicwe.command.paste")) return;
 if(isset($this->pos1[$sender->getName()], $this->pos2[$sender->getName()])){ $this->paste($sender);
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } break;
 } case "/undo": { if(!$sender->hasPermission("magicwe.command.undo")) return;
 if(!empty($this->undo[$sender->getName()])){ $this->undo($sender);
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] Nada para desfazer");
 } break;
 } case "/redo": { if(!$sender->hasPermission("magicwe.command.redo")) return;
 if(!empty($this->redo[$sender->getName()])){ $this->redo($sender);
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] Nada para refazer");
 } break;
 } case "/flip": { if(!$sender->hasPermission("magicwe.command.flip")) return;
 if(!empty($this->copy[$sender->getName()]) && isset($args[0])){ if(!in_array($args[0], array("x", "y", "z"))) return false;
 $this->flip($sender, $args[0]);
 return true;
 } elseif(!isset($args[0])){ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cArgumentos inválidos");
 } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] Nada para flip§f, §7use //copy primeiro");
 } break;
 } case "/wand": { if(!$sender->hasPermission("magicwe.command.wand")) return;
 if(empty($this->wand[$sender->getName()]) || $this->wand[$sender->getName()] === 0){ $this->wand[$sender->getName()] = 1;
 $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] §aFerramenta Com Machado ativada");
 } else{ $this->wand[$sender->getName()] = 0;
 $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cFerramenta Com Machado desativada");
 } return true;
 break;
 } case "/schem": { if(!$sender->hasPermission("magicwe.command.schem")) return;
 if(empty($args) || empty($args[0]) || empty($args[1])){ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cOpção inválida");
 } elseif($args[0] === "load"){ $this->schematics[$args[1]] = $this->loadSchematic($sender, $args[1]);
 if($this->schematics[$args[1]] instanceof SchematicLoader){ $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Esquema $args[1] Carregado com êxito no cache. Use //schem Colar para colar");
 return true;
 } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] Arquivo esquemático incorreto ou não carregado. Use //schem load <nome do arquivo> Carregar um esquema");
 } return false;
 } elseif($args[0] === "paste"){ if(isset($this->schematics[$args[1]]) && $this->schematics[$args[1]] instanceof SchematicLoader){ $success = $this->pasteSchematic($sender, $sender->getLevel(), $sender->getPosition(), $this->schematics[$args[1]]);
 if($success){ $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Esquema $args[1] colado com sucesso");
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } } $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] Arquivo esquemático incorreto ou não carregado. Use //schem load <nome do arquivo> Carregar um esquema");
 return false;
 } elseif($args[0] === "save" || $args[0] === "export"){ if(isset($this->pos1[$sender->getName()], $this->pos2[$sender->getName()])){ $success = $this->exportSchematic($sender, $args[1]);
 if($success){ $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] A seleção foi salva como $args[1].esquemático");
 return true;
 } } $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] Não é possível salva como $args[1]! Talvez um arquivo com esse nome já existe ou você não tem permissão de gravação neste caminho!");
 return false;
 } break;
 } case "/cyl": { if(!$sender->hasPermission("magicwe.command.cyl")) return;
 if(isset($args[0], $args[1])){ $this->W_cylinder($sender, $sender->getPosition(), $args[0], $args[1], $args[2]??1);
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cArgumentos ausentes");
 } break;
 } case "/hcyl": { if(!$sender->hasPermission("magicwe.command.hcyl")) return;
 if(isset($args[0], $args[1])){ $this->W_holocylinder($sender, $sender->getPosition(), $args[0], $args[1], $args[2]??1);
 $sender->getLevel()->doChunkGarbageCollection();
 return true;
 } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cArgumentos ausentes");
 } break;
 } default: { return false;
 } } } else{ $sender->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cEste comando deve ser usado no jogo");
 } return false;
 } public function wandPos1(BlockBreakEvent $event){ $sender = $event->getPlayer();
 $block = $event->getBlock();
 if($sender->hasPermission("magicwe.command.wand") && $sender->getInventory()->getItemInHand()->getId() === Item::WOODEN_AXE && $this->wand[$sender->getName()] === 1){ if($block->y > self::$MAX_BUILD_HEIGHT || $block->y < 0) $sender->sendMessage(TextFormat::GOLD . "§7[§aMWE§7] §cAviso§f: §7Você está acima §ay§f:" . self::$MAX_BUILD_HEIGHT . " §7ou abaixo §ay§f:0");
 $this->pos1[$sender->getName()] = $block;
 $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] §7Posição §f1 §7definida como §ax§f:" . $block->x . " §ay§f:" . $block->y . " §az§f:" . $block->z);
 $event->setCancelled();
 } } public function wandPos2(PlayerInteractEvent $event){ if($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
 $sender = $event->getPlayer();
 $block = $event->getBlock();
 if($sender->hasPermission("magicwe.command.wand") && $sender->getInventory()->getItemInHand()->getId() === Item::WOODEN_AXE && $this->wand[$sender->getName()] === 1){ if($block->y > self::$MAX_BUILD_HEIGHT || $block->y < 0) $sender->sendMessage(TextFormat::GOLD . "§7[§aMWE§7] §cAviso§f: §7Você está acima §ay§f:" . self::$MAX_BUILD_HEIGHT . " §7ou abaixo §ay§f:0");
 $this->pos2[$sender->getName()] = $block;
 $sender->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] §7Posição §f2 §7definida como §ax§f:" . $block->x . " §ay§f:" . $block->y . " §az§f:" . $block->z);
 $event->setCancelled();
 } } public function fill(Player $player, $blockarg){ $changed = 0;
 $time = microtime(TRUE);
 if(empty($blockarg) && $blockarg !== "0") return false;
 $level = $player->getLevel();
 $blocks = explode(",", $blockarg);
 $pos1 = $this->pos1[$player->getName()];
 $pos2 = $this->pos2[$player->getName()];
 $pos = new Vector3(min($pos1->x, $pos2->x), min($pos1->y, $pos2->y), min($pos1->z, $pos2->z));
 if(!isset($this->undo[$player->getName()])) $this->undo[$player->getName()] = [];
 $undoindex = count(array_keys($this->undo[$player->getName()]));
 $this->undo[$player->getName()][$undoindex] = [];
 for($x = $pos->x;
 $x <= max($pos1->x, $pos2->x);
 $x++){ for($y = $pos->y;
 $y <= max($pos1->y, $pos2->y);
 $y++){ if($y > self::$MAX_BUILD_HEIGHT || $y < 0) continue;
 for($z = $pos->z;
 $z <= max($pos1->z, $pos2->z);
 $z++){ if(!$level->isChunkLoaded($x >> 4, $z >> 4)) $level->loadChunk($x >> 4, $z >> 4, true);
 array_push($this->undo[$player->getName()][$undoindex], $level->getBlock($vec = new Vector3($x, $y, $z)));
 $blockstring = $blocks[array_rand($blocks, 1)];
 $block = Item::fromString($blockstring)->getBlock();
 if($block->getId() === 0 && !(strtolower(explode(":", $blockstring)[0]) == "air" || explode(":", $blockstring)[0] == "0")){ $player->sendMessage(TextFormat::RED . '§7[§aMWE§7] §cNenhum blocos§f/§citem com o nome§f:§c "' . $blockstring . '" §cencontrado§f, §cabortando');
 $player->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cPreencher Falhou.");
 return;
 } if($level->setBlock($vec, $block, false, false)) $changed++;
 } } } $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] blocos preenchidos com sucesso§f, §7Com tempo de§f " . round((microtime(TRUE) - $time), 2) . "§7s e§f " . $changed . " §7Blocos preenchidos.");
 } public function replace(Player $player, $blockarg1, $blockarg2){ $changed = 0;
 $time = microtime(TRUE);
 if((empty($blockarg1) && $blockarg1 !== "0") || (empty($blockarg2) && $blockarg2 !== "0")) return false;
 $level = $player->getLevel();
 $blocks1 = explode(",", $blockarg1);
 $blocks2 = explode(",", $blockarg2);
 $pos1 = $this->pos1[$player->getName()];
 $pos2 = $this->pos2[$player->getName()];
 $pos = new Vector3(min($pos1->x, $pos2->x), min($pos1->y, $pos2->y), min($pos1->z, $pos2->z));
 if(!isset($this->undo[$player->getName()])) $this->undo[$player->getName()] = [];
 $undoindex = count(array_keys($this->undo[$player->getName()]));
 $this->undo[$player->getName()][$undoindex] = [];
 for($x = $pos->x;
 $x <= max($pos1->x, $pos2->x);
 $x++){ for($y = $pos->y;
 $y <= max($pos1->y, $pos2->y);
 $y++){ if($y > self::$MAX_BUILD_HEIGHT || $y < 0) continue;
 for($z = $pos->z;
 $z <= max($pos1->z, $pos2->z);
 $z++){ if(!$level->isChunkLoaded($x >> 4, $z >> 4)) $level->loadChunk($x >> 4, $z >> 4, true);
 array_push($this->undo[$player->getName()][$undoindex], $level->getBlock($vec = new Vector3($x, $y, $z)));
 foreach($blocks1 as $blockstring1){ $blocka = Item::fromString($blockstring1)->getBlock();
 if($blocka->getId() === 0 && !(strtolower(explode(":", $blockstring1)[0]) == "air" || explode(":", $blockstring1)[0] == "0")){ $player->sendMessage(TextFormat::RED . '§7[§aMWE§7] §cNenhum blocos§f/§citem com o nome§f:§c "' . $blockstring1 . '" §cencontrado§f, §cabortando');
 $player->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cSubstituir falhou§f.");
 return;
 } $block1 = $blocka->getId();
 $meta1 = (explode(":", $blockstring1)[1]??false);
 if($level->getBlockIdAt($x, $y, $z) == $block1 && ($meta1 === false || $level->getBlockDataAt($x, $y, $z) == $meta1)){ $blockstring2 = $blocks2[array_rand($blocks2, 1)];
 $blockb = Item::fromString($blockstring2)->getBlock();
 if($blockb->getId() === 0 && !(strtolower(explode(":", $blockstring2)[0]) == "air" || explode(":", $blockstring2)[0] == "0")){ $player->sendMessage(TextFormat::RED . '§7[§aMWE§7] §cNenhum blocos§f/§citem com o nome§f:§c "' . $blockstring2 . '" §cencontrado§f, §cabortando');
 $player->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cReplace falhou§f.");
 return;
 } if($level->setBlock($vec, $blockb, false, false)) $changed++;
 } } } } } $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] blocos Substituídos com sucesso§f, §7Com tempo de§f " . round((microtime(TRUE) - $time), 2) . "§7s e§f " . $changed . " §7Blocos Substituídos§f.");
 } public function copy(Player $player){ $level = $player->getLevel();
 $pos1 = $this->pos1[$player->getName()];
 $pos2 = $this->pos2[$player->getName()];
 $pos = new Vector3(min($pos1->x, $pos2->x), min($pos1->y, $pos2->y), min($pos1->z, $pos2->z));
 $this->copy[$player->getName()] = [];
 $this->copypos[$player->getName()] = $pos->subtract($player->getPosition()->floor());
 for($x = 0;
 $x <= abs($pos1->x - $pos2->x);
 $x++){ for($y = 0;
 $y <= abs($pos1->y - $pos2->y);
 $y++){ if($y > self::$MAX_BUILD_HEIGHT || $y < 0) continue;
 for($z = 0;
 $z <= abs($pos1->z - $pos2->z);
 $z++){ $this->copy[$player->getName()][$x][$y][$z] = $level->getBlock($pos->add($x, $y, $z));
 } } } $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] §7Copiado com sucesso§f.");
 } public function paste(Player $player){ $time = microtime(TRUE);
 $level = $player->getLevel();
 $pos = $player->getPosition()->add($this->copypos[$player->getName()]);
 if(!isset($this->undo[$player->getName()])) $this->undo[$player->getName()] = [];
 $undoindex = count(array_keys($this->undo[$player->getName()]));
 $this->undo[$player->getName()][$undoindex] = [];
 for($x = 0;
 $x < count(array_keys($this->copy[$player->getName()]));
 $x++){ for($y = 0;
 $y < count(array_keys($this->copy[$player->getName()][$x]));
 $y++){ if($y > self::$MAX_BUILD_HEIGHT || $y < 0) continue;
 for($z = 0;
 $z < count(array_keys($this->copy[$player->getName()][$x][$y]));
 $z++){ if(!$level->isChunkLoaded($x >> 4, $z >> 4)) $level->loadChunk($x >> 4, $z >> 4, true);
 array_push($this->undo[$player->getName()][$undoindex], $level->getBlock(new Vector3($x, $y, $z)));
 $level->setBlockIdAt($pos->x + $x, $pos->y + $y, $pos->z + $z, $this->copy[$player->getName()][$x][$y][$z]->getId());
 $level->setBlockDataAt($pos->x + $x, $pos->y + $y, $pos->z + $z, $this->copy[$player->getName()][$x][$y][$z]->getDamage());
 } } } $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] §7Colado com sucesso§f, §7Com o tempo de§f " . round((microtime(TRUE) - $time), 2) . "§7s");
 } public function undo(Player $player){ $time = microtime(TRUE);
 $level = $player->getLevel();
 if(!isset($this->undo[$player->getName()])) return;
 $undo = array_pop($this->undo[$player->getName()]);
 foreach($undo as $block){ $level->setBlockIdAt($block->x, $block->y, $block->z, $block->getId());
 $level->setBlockDataAt($block->x, $block->y, $block->z, $block->getDamage());
 } $this->redo[$player->getName()][] = $undo;
 $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] §7Desfeito Com o tempo de§f " . round((microtime(TRUE) - $time), 2) . "§7s");
 } public function redo(Player $player){ $time = microtime(TRUE);
 $level = $player->getLevel();
 if(!isset($this->redo[$player->getName()])) return;
 $redo = array_pop($this->redo[$player->getName()]);
 foreach($redo as $block){ $level->setBlockIdAt($block->x, $block->y, $block->z, $block->getId());
 $level->setBlockDataAt($block->x, $block->y, $block->z, $block->getDamage());
 } $this->undo[$player->getName()] = $redo;
 $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Refeito Com o tempo de§f " . round((microtime(TRUE) - $time), 2) . "§7s");
 } public function flip(Player $player, $xyz){ if($xyz === "x"){ $this->copy[$player->getName()] = array_reverse($this->copy[$player->getName()]);
 } elseif($xyz === "y"){ foreach(array_keys($this->copy[$player->getName()]) as $block){ $this->copy[$player->getName()][$block] = array_reverse($this->copy[$player->getName()][$block]);
 } } elseif($xyz === "z"){ foreach(array_keys($this->copy[$player->getName()]) as $block){ foreach(array_keys($this->copy[$player->getName()][$block]) as $y){ $this->copy[$player->getName()][$block][$y] = array_reverse($this->copy[$player->getName()][$block][$y]);
 } } } else return false;
 $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Prancheta ligada $xyz-Axis");
 } public function W_sphere(Player $player, Position $pos, $block, $radiusX, $radiusY, $radiusZ, $filled = true, &$output = null){ $changed = 0;
 $time = microtime(TRUE);
 $block = Item::fromString($blockstring)->getBlock();
 if($block->getId() === 0 && !(strtolower(explode(":", $blockstring)[0]) == "air" || explode(":", $blockstring)[0] == "0")){ $player->sendMessage(TextFormat::RED . '§7[§aMWE§7] §cNenhum blocos§f/§citem com o nome§f:§c "' . $blockstring . '" §cencontrado§f, §cabortando');
 $player->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cFalha na criação do cilindro§f.");
 return;
 } $level = $pos->getLevel();
 $radiusX += 0.5;
 $radiusY += 0.5;
 $radiusZ += 0.5;
 $invRadiusX = 1 / $radiusX;
 $invRadiusY = 1 / $radiusY;
 $invRadiusZ = 1 / $radiusZ;
 $ceilRadiusX = (int) ceil($radiusX);
 $ceilRadiusY = (int) ceil($radiusY);
 $ceilRadiusZ = (int) ceil($radiusZ);
 $bcnt = 1;
 $nextXn = 0;
 $breakX = false;
 for($x = 0;
 $x <= $ceilRadiusX and $breakX === false;
 ++$x){ $xn = $nextXn;
 $nextXn = ($x + 1) * $invRadiusX;
 $nextYn = 0;
 $breakY = false;
 for($y = 0;
 $y <= $ceilRadiusY and $breakY === false;
 ++$y){ $yn = $nextYn;
 $nextYn = ($y + 1) * $invRadiusY;
 $nextZn = 0;
 $breakZ = false;
 for($z = 0;
 $z <= $ceilRadiusZ;
 ++$z){ $zn = $nextZn;
 $nextZn = ($z + 1) * $invRadiusZ;
 $distanceSq = WorldEditBuilder::lengthSq($xn, $yn, $zn);
 if($distanceSq > 1){ if($z === 0){ if($y === 0){ $breakX = true;
 $breakY = true;
 break;
 } $breakY = true;
 break;
 } break;
 } if($filled === false){ if(WorldEditBuilder::lengthSq($nextXn, $yn, $zn) <= 1 and WorldEditBuilder::lengthSq($xn, $nextYn, $zn) <= 1 and WorldEditBuilder::lengthSq($xn, $yn, $nextZn) <= 1){ continue;
 } } $blocktype = $block->getId();
 $this->upsetBlock2($level, $pos->add($x, $y, $z), $block);
 $count++;
 $this->upsetBlock2($level, $pos->add(-$x, $y, $z), $block);
 $count++;
 $this->upsetBlock2($level, $pos->add($x, -$y, $z), $block);
 $count++;
 $this->upsetBlock2($level, $pos->add($x, $y, -$z), $block);
 $count++;
 $this->upsetBlock2($level, $pos->add(-$x, -$y, $z), $block);
 $count++;
 $this->upsetBlock2($level, $pos->add($x, -$y, -$z), $block);
 $count++;
 $this->upsetBlock2($level, $pos->add(-$x, $y, -$z), $block);
 $count++;
 $this->upsetBlock2($level, $pos->add(-$x, -$y, -$z), $block);
 $count++;
 } } } $player->sendMessage(TextFormat::GREEN . "[MagicWE] esfera criada com sucesso, tempo" . round((microtime(TRUE) - $time), 2) . "s, " . $changed . " Blocos alterados.");
 } public function W_cylinder(Player $player, Position $pos, $blockstring, $radius, $height){ $changed = 0;
 $time = microtime(TRUE);
 $block = Item::fromString($blockstring)->getBlock();
 if($block->getId() === 0 && !(strtolower(explode(":", $blockstring)[0]) == "air" || explode(":", $blockstring)[0] == "0")){ $player->sendMessage(TextFormat::RED . '§7[§aMWE§7] §cNenhum blocos§f/§citem com o nome§f:§c "' . $blockstring . '" §cencontrado§f, §cabortando');
 $player->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cFalha na criação do cilindro§f.");
 return;
 } for($a = -$radius;
 $a <= $radius;
 $a++){ for($b = 0;
 $b < $height;
 $b++){ for($c = -$radius;
 $c <= $radius;
 $c++){ if($a * $a + $c * $c <= $radius * $radius){ if($pos->getLevel()->setBlock(new Position($pos->x + $a, $pos->y + $b, $pos->z + $c, $pos->getLevel()), $block, false, false)) $changed++;
 $changed++;
 } } } } $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Cilindro criado com sucesso§f, §7Com o tempo de§f " . round((microtime(TRUE) - $time), 2) . "§7s e§f " . $changed . " §7Blocos Criados§f.");
 } public function W_holocylinder(Player $player, Position $pos, $blockstring, $radius, $height){ $changed = 0;
 $time = microtime(TRUE);
 $block = Item::fromString($blockstring)->getBlock();
 if($block->getId() === 0 && !(strtolower(explode(":", $blockstring)[0]) == "air" || explode(":", $blockstring)[0] == "0")){ $player->sendMessage(TextFormat::RED . '§7[§aMWE§7] §cNenhum blocos§f/§citem com o nome§f:§c "' . $blockstring . '" §cencontrado§f, §cabortando');
 $player->sendMessage(TextFormat::RED . "§7[§aMWE§7] §cFalha na criação do cilindro oco§f.");
 return;
 } $changed = 0;
 for($a = -$radius;
 $a <= $radius;
 $a++){ for($b = 0;
 $b < $height;
 $b++){ for($c = -$radius;
 $c <= $radius;
 $c++){ if($a * $a + $c * $c >= ($radius - 1) * ($radius - 1)){ if($pos->getLevel()->setBlock(new Position($pos->x + $a, $pos->y + $b, $pos->z + $c, $pos->getLevel()), $block, false, false)) $changed++;
 } } } } $player->sendMessage(TextFormat::GREEN . "§7[§aMWE§7] Cilindro oco criado com sucesso§f, §7Com o tempo de§f " . round((microtime(TRUE) - $time), 2) . "§7s e§f " . $changed . " §7Blocos Criados.");
 } public function pasteSchematic(Player $player, Level $level, Position $loc, SchematicLoader $schematic){ $blocks = $schematic->getBlocksArray();
 if(!isset($this->undo[$player->getName()])) $this->undo[$player->getName()] = [];
 $undoindex = count(array_keys($this->undo[$player->getName()]));
 $this->undo[$player->getName()][$undoindex] = [];
 foreach($blocks as $block){ if($block[1] > self::$MAX_BUILD_HEIGHT) continue;
 if(!$level->isChunkLoaded($block[0] >> 4, $block[2] >> 4)) $level->loadChunk($block[0] >> 4, $block[2] >> 4, true);
 $blockloc = $loc->add($block[0], $block[1], $block[2]);
 array_push($this->undo[$player->getName()][$undoindex], $level->getBlock($blockloc));
 $level->setBlockIdAt($blockloc->getX(), $blockloc->getY(), $blockloc->getZ(), $block[3]);
 $level->setBlockDataAt($blockloc->getX(), $blockloc->getY(), $blockloc->getZ(), $block[4]);
 } return true;
 } public function loadSchematic(Player $player, $file){ $path = $this->getDataFolder() . "/schematics/" . $file . ".schematic";
 return new SchematicLoader($this, $path);
 } public function exportSchematic(Player $sender, $filename){ $blocks = '';
 $data = '';
 $pos1 = $this->pos1[$sender->getName()];
 $pos2 = $this->pos2[$sender->getName()];
 $origin = new Vector3(min($pos1->x, $pos2->x), min($pos1->y, $pos2->y), min($pos1->z, $pos2->z));
 $w = abs($pos1->x - $pos2->x) + 1;
 $h = abs($pos1->y - $pos2->y) + 1;
 $l = abs($pos1->z - $pos2->z) + 1;
 $blocks = '';
 $data = '';
 for($y = 0;
 $y < $h;
 $y++){ for($z = 0;
 $z < $l;
 $z++){ for($x = 0;
 $x < $w;
 $x++){ $block = $sender->getLevel()->getBlock($origin->add($x, $y, $z));
 $id = $block->getId();
 $damage = $block->getDamage();
 switch($id){ case 158: $id = 126;
 break;
 case 157: $id = 125;
 break;
 case 126: $id = 157;
 break;
 case 85: switch($damage){ case 1: $id = 188;
 $damage = 0;
 break;
 case 2: $id = 189;
 $damage = 0;
 break;
 case 3: $id = 190;
 $damage = 0;
 break;
 case 4: $id = 191;
 $damage = 0;
 break;
 case 5: $id = 192;
 $damage = 0;
 break;
 default: $damage = 0;
 break;
 } break;
 default: break;
 } $blocks .= chr($id);
 $data .= chr($damage);
 } } } $schematic = new SchematicExporter($blocks, $data, $w, $l, $h);
 return $schematic->saveSchematic($this->getDataFolder() . "/schematics/" . $filename . ".schematic");
 } }