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
        $languageCode = 'en'; // По умолчанию

        // Извлекаем язык из URL
        if (preg_match('#^/(en|ru)(/.*)?$#', $uri, $matches)) {
            $languageCode = $matches[1];
            $uri = $matches[2] ?? '/';
        }

        // Устанавливаем язык
        $this->container->resolve('Language')->setLanguage($languageCode);

        foreach ($this->routes as $route) {
            if (preg_match('#^' . $route['path'] . '$#', $uri, $matches)) {
                list($controller, $action) = explode('@', $route['controllerAction']);
                $controllerInstance = $this->container->resolve($controller);
                $controllerInstance->$action($matches[1]); // Передаем slug
                return;
            }
        }

        echo "404 Not Found";
    }
}