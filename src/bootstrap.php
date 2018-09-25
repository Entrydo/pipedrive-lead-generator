<?php declare(strict_types=1);

use Nette\Configurator;
use Tracy\Debugger;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator();
$configurator->setTempDirectory(__DIR__ . '/../var/temp');
$configurator->addConfig(__DIR__ . '/../config/config.neon');

$localConfig = __DIR__ . '/../config/config.local.neon';
if (is_file($localConfig)) {
	$configurator->addConfig($localConfig);
}

$configurator->enableDebugger(__DIR__ . '/../var/log');
Debugger::$strictMode = true;

return $configurator->createContainer();
