<?php

namespace ArdaaArslann;

use pocketmine\player\Player;
use pocketmine\command\{Command, CommandSender};
use ArdaaArslann\NoteMain;
use ArdaaArslann\NoteForm;

class NoteCommand extends Command {
  
  public function __construct(NoteMain $plugin){
	$this->p = $plugin;
    parent::__construct("banknotes", $this->p->config->get("Description"), "/banknotes");
    $this->setAliases($this->p->config->get("Commands"));
  }
  
  public function execute(CommandSender $g, string $label, array $args){
    if(!$g instanceof Player){
    $g->sendMessage($this->p->messages->getNested("Message.No-Player"));
    return;
    }
    $g->sendForm(new NoteForm($g));
  }
}