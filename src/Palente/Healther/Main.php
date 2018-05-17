<?php
namespace Palente\Healther;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TX;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
class Main extends PluginBase{
	public static $logger = null;
	private $config;
	public static $pr = "§a[Healther] §f";
	public static $instance;
	/**
	*s'execute au chargement du plugin
	*/
	public static function getInstance(){
	return self::$instance;
	}
	public function onLoad(){
		self::$instance =$this;
    #Plus tard
self::$logger = $this->getLogger();}
	public function onEnable(){
		
$eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
if(!$eco){
    $this->getLogger()->critical("ERROR: I couldn't Find EconomyAPI!!Error :(");
            #$this->setEnabled(false);
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
@mkdir($this->getDataFolder());
if(!file_exists($this->getDataFolder() . "config.yml")) {
	$this->saveResource("config.yml");
	self::$logger->notice("[Healther]::[Folder] I couldn't find a config....So i created it!");
}
	}
/*
*Fonction qui vérifie si le joueur a assez puis remet sa vie
*
*return Boolean(true/false)
*/
public function healPlayer(Player $player){
$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
$eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
if($eco->myMoney($player->getName()) < $config->get("cost")) return false;
//https://github.com/onebone/EconomyS/tree/master/EconomyAPI#L246-L269
$eco->reduceMoney($player->getName(), $config->get("cost"));
$player->setHealth(20);
return true;
}

public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
	$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	$cmd = $command;
if($cmd->getName() == "heal"){
	switch(count($args)){
		case 0:
			if(!$sender instanceof Player) return false;
			if(!$sender->hasPermission("healther.use")) return $sender->sendMessage($pr.TX::RED."Error: You don't have the permission to use the command /heal");
			if($sender->getLevel()->getName() == $config->get("restrictedlevel")){
				$sender->sendMessage($pr.TX::RED."ERROR: You can't regen your health in this world!");
				return false;
			}
		if(!$this->healPlayer($sender)){$sender->sendMessage($pr."You can't use the Heal command you don't have the required amount of money");
				return false;
}else{
$sender->sendMessage($pr.TX::GREEN."You have been healed");
return true;
}
		break;
		case 2:
		if(!$player->isOp()){
			$sender->sendMessage($pr.TX::RED."You are trying to acces a content who need having the permission op! You don't have it");
return false;
}
			if($args[0] == "money"){
			$mont = int($args[1]);
				$config->set("cost",$mont);
				$config->save();
				$config->reload();
				$sender->sendMessage($pr."You have setted the new cost of The /heal to".$cost);
			}elseif($args[0] == "level"){
				$leveln = $args[1];
				$level = $this->getServer()->getLevelByName($leveln);
				if(!$level instanceof Level){$sender->sendMessage($pr."Error: The level ".$leveln." seem to not exit");
							     return false;}
			$config->set("restrictedlevel",$level->getName());
				$config->save();
				$config->reload();
				$sender->sendMessage($pr.TX::YELLOW."You have successfully set the new restricted level to ".$leveln.". Now Players can't use the command /heal in this World");
			return true ;
}else{
			$sender->sendMessage($pr.TX::RED."Bad usage of command!! eg: /heal <level-cost> <namelevel-amount>")
return false;
}
		break;
	}
}
return true;
}}
