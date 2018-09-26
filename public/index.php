<?php declare (strict_types=1);

use BrandEmbassy\Slim\SlimApplicationFactory;
use Entrydo\Pipedrive\CORS\CORSMiddleware;
use Nette\DI\Container;

/** @var Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

/** @var SlimApplicationFactory $applicationFactory */
$applicationFactory = $container->getByType(SlimApplicationFactory::class);
$application = $applicationFactory->create();

$application->add($container->getByType(CORSMiddleware::class));

$application->run();
