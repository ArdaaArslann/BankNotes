<?php

namespace ArdaaArslann;

use pocketmine\plugin\PluginBase;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\data\bedrock\EnchantmentIdMap;

use ArdaaArslann\{NoteCommand, NoteEvent};

class NoteMain extends PluginBase implements Listener {
  
    public static $instance;

    public function onLoad():void{
        static::$instance = $this;
    }

    public static function getInstance(): NoteMain{
        return self::$instance;
    }

    public function onDisable():void{
        $this->getLogger()->info("§3BankNotes is Disabled");
    }

    public function onEnable():void{
        $this->getLogger()->info("§3BankNotes is Enabled");
        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");
        $this->config = $this->Config("config");
        $this->messages = $this->Config("messages");
        $this->getServer()->getPluginManager()->registerEvents(new NoteEvent($this), $this);
        $this->getServer()->getCommandMap()->register("banknotes", new NoteCommand($this));
    }

    public function Config(string $config){
        return new Config($this->getDataFolder().$config.".yml", Config::YAML);
    }

    public function getNote(int $price){
        $item = ItemFactory::getInstance()->get((int)$this->config->get("Note-Id"), (int)$this->config->get("Note-Meta"), 1);
        $item->setCustomName($this->config->get("Note-Name"));
        $item->setLore([str_replace("{money}", $price, $this->config->get("Note-Lore"))]);
        $item->getNamedTag()->setInt("money", $price);
        $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(0), 1));
        return $item;
    }


}