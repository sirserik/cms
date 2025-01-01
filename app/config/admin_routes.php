<?php


$router->get('/admin/dashboard', 'AdminController@dashboard', ['AdminMiddleware']);
$router->get('/admin/posts', 'AdminController@posts', ['AdminMiddleware']);
$router->get('/admin/users', 'AdminController@users', ['AdminMiddleware']);