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
	/**
	*s'execute au chargement du plugin
	*/
	public function onLoad(){
		self::$logger = $this->getLogger();
		self::$logger->info("[Healther]Build: BETA!!!");
		self::$logger->info("[Healther]By Palente");
		self::$logger->info("[Healther]twitter: @Palente_Gaming");
		self::$logger->info("[Healther]Version: 1");
}
	public function onEnable(){
self::$logger->info("[Healther] Enabled");
$eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
if(!$eco){
    $this->getLogger()->critical("ERROR: EconomyAPI???.");
            $this->setEnabled(false);
            $this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin("Healther"));
        }
@mkdir($this->getDataFolder());
if(!file_exists($this->getDataFolder() . "config.yml")) {
	$this->saveResource("config.yml");
	self::$logger->notice("[Healther]::[Folder] The file config.yml was created Successfully");
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
}
public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
	$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
if($cmd->getName() == "hl"){
	switch(count($args)){
		case 0:
			if(!$sender instanceof Player) return false;
			if(!$sender->hasPermission("healther.use")) return $sender->sendMessage($pr.TX::RED."Error: You can't use that command");
			if($sender->getLevel()->getName() == $config->get("restrictedlevel")){
				$sender->sendMessage($pr.TX::RED."ERROR: You can't regen your health with that in this world!");
				return false;
			}
		$this->healPlayer($this->getServer()->getPlayer($sender));
		break;
		case 2:
			if($args[0] == "param"){
				if(!$player->isOp()) return false;
			$mont = int($args[1]);
				$config->set("cost",$mont);
				$config->save();
				$config->reload();
				$sender->sendMessage($pr."The new cost of the /hl is now set to".$cost);
			}else{
				return false;}
		break;
	}
}
}
}
	
