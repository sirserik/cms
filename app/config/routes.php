<?php


$router->add('home', '/', 'HomeController@index');
$router->add('post.show', '/post/{slug}', 'PostController@show');