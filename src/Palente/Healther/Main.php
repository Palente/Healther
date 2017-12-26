<?php
namespace Palente\Healther;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;;
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
        }
@mkdir($this->getDataFolder());
f(!file_exists($this->getDataFolder() . "config.yml")) {
	$this->saveResource("config.yml");
	self::$logger->notice("[Healther]::[Folder] The file config.yml was created Successfully");
}else{
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
}
