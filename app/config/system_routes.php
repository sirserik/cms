<?php


$router->get('/system/maintenance', 'SystemController@enableMaintenanceMode', ['AdminMiddleware']);
$router->get('/system/development', 'SystemController@enableDevelopmentMode', ['AdminMiddleware']);
$router->get('/system/production', 'SystemController@enableProductionMode', ['AdminMiddleware']);
$router->get('/system/clear-cache', 'SystemController@clearCache', ['AdminMiddleware']);
$router->get('/system/clear-logs', 'SystemController@clearLogs', ['AdminMiddleware']);
$router->get('/system/mode', 'SystemController@getMode', ['AdminMiddleware']);