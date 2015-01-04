<?php
namespace FoodRecipe\Command;

use FoodRecipe\System\Framework\ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlerCommand extends ConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('crawler')
            ->setDescription('Runs crawler');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Guzzle\Http\Client $httpClient */
        $httpClient = self::$container['httpClient'];
    }
}
