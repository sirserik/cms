<?php
$router->get('/products', 'ProductController@index');
$router->get('/product/{id}', 'ProductController@show');
$router->get('/', 'HomeController@index');
$router->get('/post/{slug}', 'PostController@show');
$router->get('/login', 'AuthController@loginForm');
$router->get('/logout', 'AuthController@logout');
$router->post('/login', 'AuthController@login');
$router->post('/upload', 'UploadController@upload', ['AuthMiddleware']);