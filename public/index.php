<?php declare (strict_types=1);

use BrandEmbassy\Slim\SlimApplicationFactory;
use Nette\DI\Container;

/** @var Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

/** @var SlimApplicationFactory $applicationFactory */
$applicationFactory = $container->getByType(SlimApplicationFactory::class);
$application = $applicationFactory->create();

$application->run();
