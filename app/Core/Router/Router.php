<?php

namespace App\Core\Router;

use App\Core\DI\Container;
use App\Core\Http\Request;
use App\Core\Http\Response;
use Exception;

class Router
{
    private $routes = [];
    private $container;
    private $namedRoutes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Добавление маршрута для GET-запроса.
     */
    public function get($path, $controllerAction, $middleware = [], $name = null)
    {
        $this->addRoute('GET', $path, $controllerAction, $middleware, $name);
    }

    /**
     * Добавление маршрута для POST-запроса.
     */
    public function post($path, $controllerAction, $middleware = [], $name = null)
    {
        $this->addRoute('POST', $path, $controllerAction, $middleware, $name);
    }

    /**
     * Добавление маршрута для PUT-запроса.
     */
    public function put($path, $controllerAction, $middleware = [], $name = null)
    {
        $this->addRoute('PUT', $path, $controllerAction, $middleware, $name);
    }

    /**
     * Добавление маршрута для PATCH-запроса.
     */
    public function patch($path, $controllerAction, $middleware = [], $name = null)
    {
        $this->addRoute('PATCH', $path, $controllerAction, $middleware, $name);
    }

    /**
     * Добавление маршрута для DELETE-запроса.
     */
    public function delete($path, $controllerAction, $middleware = [], $name = null)
    {
        $this->addRoute('DELETE', $path, $controllerAction, $middleware, $name);
    }

    /**
     * Добавление маршрута для любого HTTP-метода.
     */
    public function any($path, $controllerAction, $middleware = [], $name = null)
    {
        $this->addRoute(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], $path, $controllerAction, $middleware, $name);
    }

    /**
     * Добавление ресурсного маршрута.
     */
    public function resource($path, $controller, $middleware = [])
    {
        $this->get("$path", "$controller@index", $middleware, "$path.index");
        $this->get("$path/create", "$controller@create", $middleware, "$path.create");
        $this->post("$path", "$controller@store", $middleware);
        $this->get("$path/{id}", "$controller@show", $middleware, "$path.show");
        $this->get("$path/{id}/edit", "$controller@edit", $middleware, "$path.edit");
        $this->put("$path/{id}", "$controller@update", $middleware);
        $this->patch("$path/{id}", "$controller@update", $middleware);
        $this->delete("$path/{id}", "$controller@destroy", $middleware);
    }

    /**
     * Добавление маршрута для перенаправления.
     */
    public function redirect($from, $to, $status = 301)
    {
        $this->get($from, function () use ($to, $status) {
            $response = new Response();
            $response->redirect($to, $status);
        });
    }

    /**
     * Добавление маршрута для статической страницы.
     */
    public function view($path, $view, $data = [], $middleware = [])
    {
        $this->get($path, function () use ($view, $data) {
            extract($data);
            require __DIR__ . "/../../views/$view.php";
        }, $middleware);
    }

    /**
     * Добавление fallback маршрута.
     */
    public function fallback($controllerAction)
    {
        $this->any('{any}', $controllerAction)->where('any', '.*');
    }

    /**
     * Добавление маршрута.
     */
    private function addRoute($method, $path, $controllerAction, $middleware = [], $name = null)
    {
        $methods = is_array($method) ? $method : [$method];
        foreach ($methods as $m) {
            $this->routes[] = [
                'method' => $m,
                'path' => $path,
                'controllerAction' => $controllerAction,
                'middleware' => $middleware,
            ];
        }

        if ($name) {
            $this->namedRoutes[$name] = $path;
        }
    }

    /**
     * Генерация URL по имени маршрута.
     */
    public function route($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new Exception("Route name not found: {$name}");
        }

        $path = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $path = str_replace("{{$key}}", $value, $path);
        }

        return $path;
    }

    /**
     * Диспетчеризация запроса.
     */
    public function dispatch()
    {
        try {
            // Создание объекта запроса
            $request = new Request();

            // Создание объекта ответа
            $response = new Response();

            // Обработка глобальных middleware
            if (!$this->handleGlobalMiddleware($request, $response)) {
                return;
            }

            // Поиск подходящего маршрута
            $route = $this->findMatchingRoute($request);

            if (!$route) {
                $response->setStatusCode(404)->setContent('Route not found')->send();
                return;
            }

            // Обработка маршрутных middleware
            if (!$this->handleRouteMiddleware($route['middleware'], $request, $response)) {
                return;
            }

            // Вызов контроллера и действия
            $controllerResponse = $this->invokeController($route, $request);

            // Отправка ответа
            $response->setContent($controllerResponse)->send();
        } catch (Exception $e) {
            $this->handleException($e, $response);
        }
    }

    /**
     * Обрабатывает глобальные middleware.
     */
    private function handleGlobalMiddleware(Request $request, Response $response): bool
    {
        $globalMiddleware = ['MaintenanceMiddleware', 'DevelopmentMiddleware'];
        return $this->runMiddleware($globalMiddleware, $request, $response);
    }

    /**
     * Ищет подходящий маршрут для запроса.
     */
    private function findMatchingRoute(Request $request): ?array
    {
        foreach ($this->routes as $route) {
            if (
                $route['method'] === $request->method() &&
                $this->matchPath($route['path'], $request->uri(), $params)
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
    private function handleRouteMiddleware(array $middlewareList, Request $request, Response $response): bool
    {
        return $this->runMiddleware($middlewareList, $request, $response);
    }

    /**
     * Вызывает контроллер и действие.
     */
    private function invokeController(array $route, Request $request)
    {
        list($controller, $action) = explode('@', $route['controllerAction']);

        $this->validateController($controller, $action);

        $controllerInstance = $this->container->resolve($controller);
        return $controllerInstance->$action(array_merge($route['params'], $request->queryParams()));
    }

    /**
     * Проверяет существование контроллера и метода.
     */
    private function validateController(string $controller, string $action): void
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
    private function handleException(Exception $e, Response $response): void
    {
        $errorHandler = $this->container->resolve('ErrorHandler');
        $errorHandler->handleException($e, $response);
    }

    /**
     * Проверка соответствия пути маршрута.
     */
    private function matchPath(string $routePath, string $uri, array &$params = []): bool
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
    private function runMiddleware(array $middlewareList, Request $request, Response $response): bool
    {
        foreach ($middlewareList as $middlewareClass) {
            $middlewareInstance = $this->container->resolve($middlewareClass);
            $result = $middlewareInstance->handle($request, $response, function ($request, $response) {
                return true;
            });

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}