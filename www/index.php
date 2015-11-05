<?php

use Aura\Router\RouterFactory;

use Controllers\HomePageController;


mb_internal_encoding("UTF-8");

//Register composers autoload
require_once '../vendor/autoload.php';

//Load config for Propel(ORM)
include("generated-conf/config.php");

//Load helper classes.
require_once("Helpers/BasicHelper.php");

//Load by namespace in thi project
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ ;

    $file = $base_dir . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});

//And the magic comes
$router_factory = new RouterFactory;
$router = $router_factory->newInstance();

//load routed from file
require_once("routes.php");

//get route
$route = $router->match(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER);

//dispatch route
if (isset($route->params['action'])){
	$params = $route->params;
	$action = explode('.', $params['action']);
	$controller = "Controllers\\".$action[0]."Controller";
	$instance = new $controller;

	//uset action so its not passed as first parametr probably use blacklist becaseof there can be some other params from route
	unset($params['action']);

	call_user_func_array(array($instance, $action[1]), $params);
}

//TODO: Handle Failure