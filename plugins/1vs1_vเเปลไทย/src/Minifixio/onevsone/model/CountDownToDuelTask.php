<?php

namespace Minifixio\onevsone\model;

use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\Plugin;
use pocketmine\level\sound\NoteblockSound;


class CountDownToDuelTask extends PluginTask{
	
	const COUNTDOWN_DURATION = 5;
	
	private $arena;
	private $countdownValue;
	
	public function __construct(Plugin $owner, Arena $arena){
		parent::__construct($owner);
		$this->arena = $arena;
		$this->countdownValue = CountDownToDuelTask::COUNTDOWN_DURATION;
	}
	
	public function onRun($currentTick){
		if(count($this->arena->players) < 2){
			$this->arena->abortDuel();
		}
		else{
			$player1 = $this->arena->players[0];
			$player2 = $this->arena->players[1];
			
			if(!$player1->isOnline() || !$player2->isOnline()){
				$this->arena->abortDuel();
			}
			else{
				$player1->sendTitle(TextFormat::GOLD . TextFormat::BOLD . $this->countdownValue . TextFormat::RESET . " §aว§fิ§bน§dา§6ท§aี§b..");
				$player1->getLevel()->addSound(new NoteblockSound($player1));
				$player2->sendTitle(TextFormat::GOLD . TextFormat::BOLD . $this->countdownValue . TextFormat::RESET . " §aว§fิ§bน§dา§6ท§aี§b..");
				$player2->getLevel()->addSound(new NoteblockSound($player2));
				$this->countdownValue--;
				
				// If countdown is finished, start the duel and stop the task
				if($this->countdownValue == 0){
					$this->arena->startDuel();
				}
			}
		}
	}
	
}