<?php

$router->addGet('HomePage.index', '/');

$router->addGet('User.auto_complete', '/users/auto-complete');
$router->addGet('User.show', '/users/{id}');
$router->addGet('User.logout', '/logout');
$router->addPost('User.create', '/register');
$router->addPost('User.login', '/login');
$router->addGet('User.fb_login', '/fb-login-callback');
$router->addGet('User.g_login', '/google-callback');
$router->addGet('User.confirm_email', '/confirm');

$router->addGet('Note.show_all', '/notes');
$router->addGet('Note.add', '/notes/add');
$router->addGet('Note.show', '/notes/{id}');
$router->addGet('Note.edit', '/notes/edit/{id}');
$router->addPost('Note.save', '/notes/edit/{id}');
$router->addPost('Note.create', '/notes/add');
$router->addGet('Note.change_state', '/note/{id}/state/');
$router->addPost('Note.comment', '/notes/{id}/comment');

$router->addPost('Shared.update', '/share/update/{id}');
$router->addPost('Shared.add_to_note', '/share/add/note/{id}');
$router->addPost('Shared.add_to_category', '/share/add/category/{id}');
$router->addGet('Shared.remove', '/share/remove/{id}');
$router->addGet('Shared.possible', '/share/possible');
$router->addPost('Shared.new_group', '/share/new_group');

$router->addPost('Group.add', '/group/add');

$router->addGet('Settings.index', '/settings');
$router->addGet('Settings.category', '/settings/category');
$router->addGet('Settings.groups', '/settings/groups');
$router->addGet('Settings.group', '/settings/group/{id}');

$router->addPost('Category.add', '/category/add');
$router->addGet('Category.remove', '/category/{id}/remove');
$router->addGet('Note.show_category', '/category/{id}');