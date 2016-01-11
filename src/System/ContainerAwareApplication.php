<?php
namespace FoodRecipe\System;

use Symfony\Component\Console\Application;
use Pimple\Container;

/**
 * Class ContainerAwareApplication
 * @package FoodRecipe\System
 */
class ContainerAwareApplication extends Application
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * Sets a container instance onto this application.
     *
     * @param Container $container
     *
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get the Container.
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Returns a service contained in the application container or null if none is found with that name.
     *
     * This is a convenience method used to retrieve an element from the Application container without having to assign
     * the results of the getContainer() method in every call.
     *
     * @param string $name Name of the service.
     *
     * @see self::getContainer()
     *
     * @api
     *
     * @return mixed|null
     */
    public function getService($name)
    {
        return isset($this->container[$name]) ? $this->container[$name] : null;
    }
}
