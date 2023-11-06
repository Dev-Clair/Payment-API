<?php

use DI\Container;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

/**
 * @var Container $container
 */
$container = require_once __DIR__ . '/container/container.php';

return ConsoleRunner::run(new SingleManagerProvider($container->get(EntityManager::class)));
