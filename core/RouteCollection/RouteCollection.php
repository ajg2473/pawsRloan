<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/13/18
 * Time: 2:52 PM
 */

namespace Bolzen\Core\RouteCollection;

use Bolzen\Core\Config\Config;
use Bolzen\Core\Config\ConfigInterface;
use Symfony\Component\Routing\Route;

class RouteCollection implements RouteCollectionInterface
{
    private $routes;
    private $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->routes = new \Symfony\Component\Routing\RouteCollection();

    }

    /**
     * This method returns the symfony route collection instance
     * @return \Symfony\Component\Routing\RouteCollection - symfony route collection instance
     */
    public function getRouteCollection(): \Symfony\Component\Routing\RouteCollection
    {
        return $this->routes;
    }

    /**
     * This method takes a given name and route and add it to the symfony's
     * route collection
     * @param string $name The route name
     * @param Route $route A Route instance
     */
    public function add(string $name, Route $route): void
    {
        //append the project directory
        $path = strtolower($this->config->getProjectDirectory().$route->getPath());

        //correct the path by override it
        $route->setPath($path);

        $this->routes->add($name, $route);
    }

    /**
     * This method allows to add controller only using the add function.
     * However, a random route name is generated
     * @param Route $route A Route instance
     */
    public function addControllerOnly(Route $route): void
    {
        $this->add($this->generateUniqueName(), $route);
    }

    /**
     * This method allows to add controller only using the add function.
     * However, a random route name is generated
     * @param Route $route A Route instance
     */
    public function addAjax(Route $route): void
    {
        $this->addControllerOnly($route);
    }

    private function generateUniqueName()
    {
        $name ="";
        while (true) {
            $bytes = openssl_random_pseudo_bytes(6, $cstrong);
            $name   = bin2hex($bytes);

            //name is not already taken so we can use it
            if ($this->routes->get($name)===null) {
                break;
            }
        }

        return $name;
    }
}