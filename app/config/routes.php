<?php

$router->add('home', '/', 'PostController@index');
$router->add('post.show', '/post/{slug}', 'PostController@show');
$router->add('api.post', '/api/post/{slug}', 'ApiController@getPost');
$router->add('admin.dashboard', '/admin/dashboard', 'AdminController@dashboard');