<?php
namespace FoodRecipe\System\Framework;

use Pimple\Container;
use Symfony\Component\Console\Command\Command;

class ConsoleCommand extends Command
{
    /** @var \Pimple\Container $container container object */
    protected static $container;

    /**
     * Injects container to console command
     *
     * @param \Pimple\Container $container container
     */
    public static function setContainer(Container $container)
    {
        static::$container = $container;
    }
}
