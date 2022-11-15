<?php

namespace ArdaaArslann;

use ArdaaArslann\NoteMain;
use pocketmine\{player\Player, Server};
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerInteractEvent;

class NoteEvent implements Listener {
  
  public function __construct(NoteMain $plugin){
    $this->p = $plugin;
  }
  
  public function onInteract(PlayerInteractEvent $e):void{
    $g = $e->getPlayer();
    $item = $g->getInventory()->getItemInHand();
    $block = $e->getBlock();
    if($item->getId() !== (int)$this->p->config->get("Note-Id"))  return;
	if($e->getAction() !== 1 and $e->getAction() !== 3) return;
    if(!$item->getNamedTag()->getTag("money")) return;
    if($block->getId() === 199) return;
    if(!$g->hasPermission("banknotes.use")){
    $g->sendMessage($this->p->messages->getNested("Message.Use-Succes"));
    return;
    }
    $price = $item->getNamedTag()->getInt("money");
    $item->setCount($item->getCount() - 1);
    $g->getInventory()->setItemInHand($item);
    EconomyAPI::getInstance()->addMoney($g, $price);
    $money = EconomyAPI::getInstance()->myMoney($g);
    $g->sendMessage(str_replace(["{money}", "{price}"], [$money, $price], $this->p->messages->getNested("Message.Use-Succes")));
  }
}