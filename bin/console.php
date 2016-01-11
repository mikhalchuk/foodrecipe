<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use FoodRecipe\System\ContainerAwareApplication;

use FoodRecipe\Command\CrawlerCommand;

$container = require_once dirname(__FILE__) . '/../config/container.php';

Monolog\ErrorHandler::register($container['logger']);

$application = new ContainerAwareApplication();
$application->setContainer($container);

$application->add(new CrawlerCommand());
$application->run();
