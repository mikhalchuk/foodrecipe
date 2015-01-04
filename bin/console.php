<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use FoodRecipe\System\Framework\ConsoleCommand;

use FoodRecipe\Command\CrawlerCommand;

$dc = require_once dirname(__FILE__) . '/../config/container.php';
ConsoleCommand::setContainer($dc);

$application = new Application();
$application->add(new CrawlerCommand());
$application->run();
