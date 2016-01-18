<?php

$router->addGet('HomePage.index', '/');

$router->addGet('User.show', '/users/{id}');
$router->addGet('User.logout', '/logout');
$router->addPost('User.create', '/register');
$router->addPost('User.login', '/login');

$router->addGet('Note.show_all', '/notes');
$router->addGet('Note.add', '/notes/add');
$router->addGet('Note.show', '/notes/{id}');
$router->addGet('Note.edit', '/notes/edit/{id}');
$router->addPost('Note.save', '/notes/edit/{id}');
$router->addPost('Note.create', '/notes/add');
$router->addGet('Note.change_state', '/note/{id}/state/');
$router->addPost('Note.comment', '/notes/{id}/comment');

$router->addPost('Shared.update', '/share/update/{id}');

$router->addGet('User.fb_login', '/fb-login-callback');
$router->addGet('User.g_login', '/google-callback');