<?php

use Aura\Router\RouterFactory;

use Controllers\HomePageController;

session_start();

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

//Load routed from file
require_once("routes.php");

//Get route
$route = $router->match(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER);

//Dispatch route
if (isset($route->params['action'])){
	$params = $route->params;
	$action = explode('.', $params['action']);
	$controller = "Controllers\\".$action[0]."Controller";
	$instance = new $controller;

	//Unset action so its not passed as first parametr probably use blacklist becase of there can be some other params from route
	unset($params['action']);

	call_user_func_array(array($instance, $action[1]), $params);
}
else{
	$failure = $router->getFailedRoute();

	//TODO render something nicer
	if ($failure->failedMethod()) {
		header("HTTP/1.0 405 Method Not Allowed");
		echo("405 Method Not Allowed");
	} elseif ($failure->failedAccept()) {
		header("HTTP/1.0 406 Not Acceptable");
		echo("406 Not Acceptable");
	} else {
		header("HTTP/1.0 404 Not Found");
		echo("404 Not found");
	}
}