<?php

$router->addGet('HomePage.index', '/');
$router->addGet('HomePage.filter', '/filter');

$router->addGet('User.show', '/users/{id}');
$router->addGet('User.add', '/register');
$router->addPost('User.create', '/register');