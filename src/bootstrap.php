<?php declare(strict_types=1);

use Nette\DI\Compiler;
use Nette\DI\ContainerLoader;

require __DIR__ . '/../vendor/autoload.php';

$loader = new ContainerLoader(__DIR__ . '/../var/temp');
$class = $loader->load(function (Compiler $compiler) {
	$compiler->loadConfig(__DIR__ . '/../config/config.neon');
});

return new $class;
