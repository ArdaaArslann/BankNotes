<?php

namespace ArdaaArslann;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Input;
use pocketmine\player\Player;
use onebone\economyapi\EconomyAPI;

class NoteForm extends CustomForm {

    public function __construct($g){
        $this->p = NoteMain::getInstance();
        $min = $this->p->config->get("Min-Money");
        $max = $this->p->config->get("Max-Money");
        $money = EconomyAPI::getInstance()->myMoney($g);
        parent::__construct($this->p->messages->getNested("UI.Title"),
        [
        new Label("label", str_replace(["{minimum}", "{maximum}", "{money}"], [$min, $max, $money], $this->p->messages->getNested("UI.Content"))),
        new Input("input", "", $this->p->messages->getNested("UI.Input-Example"))
        ], function(Player $g, CustomFormResponse $args) use($min, $max): void{
        $money = EconomyAPI::getInstance()->myMoney($g);
        $input = $args->getString("input");
        if(empty($args->getString("input"))){
        $g->sendMessage(str_replace(["{minimum}", "{maximum}", "{money}", "{input}"], [$min, $max, $money, $input], $this->p->messages->getNested("Message.Null-Value")));
        return;
        }

        if(!is_numeric($input)){
        $g->sendMessage(str_replace(["{minimum}", "{maximum}", "{money}", "{input}"], [$min, $max, $money, $input], $this->p->messages->getNested("Message.Not-Numeric")));
        return;
        }

        if(ceil($input) < $min){
        $g->sendMessage(str_replace(["{minimum}", "{maximum}", "{money}", "{input}"], [$min, $max, $money, $input], $this->p->messages->getNested("Message.Minimum-Value")));
        return;
        }

        if(ceil($input) > $max){
        $g->sendMessage(str_replace(["{minimum}", "{maximum}", "{money}", "{input}"], [$min, $max, $money, $input], $this->p->messages->getNested("Message.Maximum-Value")));
        return;
        }

        if(ceil($input) > $money){
        $g->sendMessage(str_replace(["{minimum}", "{maximum}", "{money}", "{input}"], [$min, $max, $money, $input], $this->p->messages->getNested("Message.Not-Have-Money")));
        return;
        }

        if(!$g->getInventory()->canAddItem($item = $this->p->getNote(ceil($input)))){
        $g->sendMessage(str_replace(["{minimum}", "{maximum}", "{money}", "{input}"], [$min, $max, $money, $input], $this->p->messages->getNested("Message.Cant-Add-Item")));
        return;
        }

        EconomyAPI::getInstance()->reduceMoney($g, ceil($input));
        $g->getInventory()->addItem($item);
        $money = EconomyAPI::getInstance()->myMoney($g);
        $g->sendMessage(str_replace(["{minimum}", "{maximum}", "{money}", "{input}"], [$min, $max, $money, $input], $this->p->messages->getNested("Message.Create-Succes")));
        return;
        });
    }
}