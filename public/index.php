<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use App\Core\DI\Container;
use App\Core\Router\Router;

session_start();

$container = new Container();
$router = new Router($container);

// Привязки
$container->bind('View', function() {
    return new View();
});

$container->bind('PostModel', function() use ($container) {
    $pdo = new PDO('mysql:host=localhost;dbname=multilang_cms', 'root', '');
    return new PostModel($pdo);
});

$container->bind('Auth', function() {
    return new Auth();
});

$container->bind('Cache', function() {
    return new Cache();
});

$container->bind('Logger', function() {
    return new Logger();
});

$container->bind('Mailer', function() {
    return new Mailer();
});

require_once __DIR__ . '/../app/config/routes.php';

$router->dispatch();

//$cache = $container->resolve('Cache');
//$cache->set('key', 'value', 3600);

//Logger::log('Something happened!');

//$mailer = $container->resolve('Mailer');
//$mailer->send('test@example.com', 'Subject', 'Message');

//http://localhost:8000/api/post/hello-world
//http://localhost:8000/admin/dashboard