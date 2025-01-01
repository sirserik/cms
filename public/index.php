<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use App\Controllers\BaseController;
use App\Controllers\SystemController;
use App\Core\Auth\Auth;
use App\Core\Cache\Cache;
use App\Core\DI\Container;
use App\Core\ErrorHandler\ErrorHandler;
use App\Core\FileManager\FileManager;
use App\Core\Logger\Logger;
use App\Core\Mailer\Mailer;
use App\Core\Router\Router;
use App\Core\SessionManager\SessionManager;
use App\Models\BaseModel;
use App\Models\PostModel;

session_start();

$container = new Container();
$router = new Router($container);

// Привязки
$container->bind('View', function() {
    return new View();
});
$container->bind('BaseModel', function() use ($container) {
    $pdo = new PDO('mysql:host=localhost;dbname=multilang_cms', 'root', '');
    $cache = $container->resolve('Cache');
    $logger = $container->resolve('Logger');
    return new BaseModel($pdo, $cache, $logger);
});

$container->bind('BaseController', function() use ($container) {
    $view = $container->resolve('View');
    $language = $container->resolve('Language');
    $auth = $container->resolve('Auth');
    return new BaseController($view, $language, $auth);
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
$container->bind('SessionManager', function() {
    return new SessionManager();
});
$container->bind('Mailer', function() {
    return new Mailer();
});
$container->bind('FileManager', function() {
    return new FileManager();
});
$container->bind('SystemController', function() use ($container) {
    $cache = $container->resolve('Cache');
    $logger = $container->resolve('Logger');
    return new SystemController($cache, $logger);
});
$container->bind('ErrorHandler', function() use ($container) {
    $logger = $container->resolve('Logger');
    return new ErrorHandler($logger);
});

// Регистрация обработчиков ошибок и исключений
$errorHandler = $container->resolve('ErrorHandler');
$errorHandler->register();
require_once __DIR__ . '/../app/config/routes.php';
require_once __DIR__ . '/../app/config/admin_routes.php';
require_once __DIR__ . '/../app/config/system_routes.php';

$router->dispatch();

//$cache = $container->resolve('Cache');
//$cache->set('key', 'value', 3600);

//Logger::log('Something happened!');

//$mailer = $container->resolve('Mailer');
//$mailer->send('test@example.com', 'Subject', 'Message');

//http://localhost:8000/api/post/hello-world
//http://localhost:8000/admin/dashboard

//$fileManager = $container->resolve('FileManager');
//
//// Запись в файл
//$fileManager->write('test.txt', 'Hello, World!');
//
//// Чтение файла
//echo $fileManager->read('test.txt'); // Вывод: Hello, World!

//$fileManager = $container->resolve('FileManager');

//// Создание директории
//$fileManager->createDirectory('uploads');
//
//// Список файлов в директории
//$files = $fileManager->listFiles('uploads');
//print_r($files);
//
//// Удаление директории
//$fileManager->deleteDirectory('uploads');

//if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
//    $fileManager = $container->resolve('FileManager');
//
//    $uploadDir = 'uploads/';
//    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
//
//    // Перемещение загруженного файла
//    $fileManager->move($_FILES['file']['tmp_name'], $uploadFile);
//
//    echo "File uploaded successfully!";
//}

use App\Core\AppMode;

//// Включение режима технических работ
//AppMode::enableMaintenanceMode();
//
//// Включение режима разработчика
//AppMode::enableDevelopmentMode();
//
//// Включение рабочего режима
//AppMode::enableProductionMode();
//
//// Получение текущего режима
//$mode = AppMode::getMode();
//echo "Current mode: $mode";