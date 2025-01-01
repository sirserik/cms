<?php
namespace App\Core\Router;

use App\Core\DI\Container;

class Router
{
    private $routes = [];
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function add($name, $path, $controllerAction)
    {
        $this->routes[$name] = [
            'path' => $path,
            'controllerAction' => $controllerAction,
        ];
    }

    public function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        foreach ($this->routes as $route) {
            if ($route['path'] === $uri) {
                list($controller, $action) = explode('@', $route['controllerAction']);
                $controllerInstance = $this->container->resolve($controller);
                $controllerInstance->$action();
                return;
            }
        }

        echo "404 Not Found";
    }
}