<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Core\DI\Container;
use App\Core\Language\Language;
use App\Core\Router\Router;
use App\Models\PostModel;

$container = new Container();
$router = new Router($container);

require_once __DIR__ . '/../app/config/routes.php';

$container->bind('HomeController', function() use ($container) {
    $language = new Language($config['default_language']);
    return new HomeController($language);
});
$container->bind('PostModel', function() use ($container) {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    return new PostModel($pdo);
});

$container->bind('PostController', function() use ($container) {
    $postModel = $container->resolve('PostModel');
    return new PostController($postModel);
});
$router->dispatch();