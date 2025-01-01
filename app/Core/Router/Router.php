<?php

namespace App\Core\Router;

use App\Core\DI\Container;
use Exception;

class Router
{
    private $routes = [];
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Добавление маршрута для GET-запроса.
     */
    public function get($path, $controllerAction, $middleware = [])
    {
        $this->addRoute('GET', $path, $controllerAction, $middleware);
    }

    /**
     * Добавление маршрута для POST-запроса.
     */
    public function post($path, $controllerAction, $middleware = [])
    {
        $this->addRoute('POST', $path, $controllerAction, $middleware);
    }

    /**
     * Добавление маршрута.
     */
    private function addRoute($method, $path, $controllerAction, $middleware)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controllerAction' => $controllerAction,
            'middleware' => $middleware,
        ];
    }

    /**
     * Диспетчеризация запроса.
     */
    public function dispatch()
    {
        try {
            // Создание объекта запроса
            $request = $this->createRequest();

            // Обработка глобальных middleware
            if (!$this->handleGlobalMiddleware($request)) {
                return $this->respondWithError(403, 'Access Denied');
            }

            // Поиск подходящего маршрута
            $route = $this->findMatchingRoute($request);

            if (!$route) {
                return $this->respondWithError(404, 'Route not found');
            }

            // Обработка маршрутных middleware
            if (!$this->handleRouteMiddleware($route['middleware'], $request)) {
                return $this->respondWithError(403, 'Access Denied');
            }

            // Вызов контроллера и действия
            $this->invokeController($route, $request);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Создает объект запроса.
     */
    private function createRequest()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $queryParams = [];
        parse_str($_SERVER['QUERY_STRING'] ?? '', $queryParams);

        return [
            'uri' => $uri,
            'method' => $method,
            'queryParams' => $queryParams,
        ];
    }

    /**
     * Обрабатывает глобальные middleware.
     */
    private function handleGlobalMiddleware($request)
    {
        $globalMiddleware = ['MaintenanceMiddleware', 'DevelopmentMiddleware'];
        return $this->runMiddleware($globalMiddleware, $request);
    }

    /**
     * Ищет подходящий маршрут для запроса.
     */
    private function findMatchingRoute($request)
    {
        foreach ($this->routes as $route) {
            if (
                $route['method'] === $request['method'] &&
                $this->matchPath($route['path'], $request['uri'], $params)
            ) {
                $route['params'] = $params;
                return $route;
            }
        }
        return null;
    }

    /**
     * Обрабатывает middleware для маршрута.
     */
    private function handleRouteMiddleware($middlewareList, $request)
    {
        return $this->runMiddleware($middlewareList, $request);
    }

    /**
     * Вызывает контроллер и действие.
     */
    private function invokeController($route, $request)
    {
        list($controller, $action) = explode('@', $route['controllerAction']);

        $this->validateController($controller, $action);

        $controllerInstance = $this->container->resolve($controller);
        $controllerInstance->$action(array_merge($route['params'], $request['queryParams']));
    }

    /**
     * Проверяет существование контроллера и метода.
     */
    private function validateController($controller, $action)
    {
        if (!class_exists($controller)) {
            throw new Exception("Controller not found: {$controller}");
        }

        if (!method_exists($controller, $action)) {
            throw new Exception("Method not found: {$controller}@{$action}");
        }
    }

    /**
     * Обрабатывает исключения.
     */
    private function handleException(Exception $e)
    {
        $errorHandler = $this->container->resolve('ErrorHandler');
        $errorHandler->handleException($e);
    }

    /**
     * Возвращает ответ с ошибкой.
     */
    private function respondWithError($statusCode, $message)
    {
        http_response_code($statusCode);
        echo json_encode(['error' => $message]);
    }

    /**
     * Проверка соответствия пути маршрута.
     */
    private function matchPath($routePath, $uri, &$params = [])
    {
        $routePath = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $routePath = "#^$routePath$#";

        if (preg_match($routePath, $uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Выполнение Middleware.
     */
    private function runMiddleware($middleware, $request)
    {
        foreach ($middleware as $middlewareClass) {
            $middlewareInstance = $this->container->resolve($middlewareClass);
            $request = $middlewareInstance->handle($request, function ($request) {
                return $request;
            });

            if (!$request) {
                return false;
            }
        }

        return true;
    }
}