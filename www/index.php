<?php

use Aura\Router\RouterFactory;
use Propel\Runtime\Propel;
use Helpers\LogHelper;
use Helpers\ConfigHelper;

session_start();

mb_internal_encoding("UTF-8");


//Register composers autoload
require_once '../vendor/autoload.php';

//Load config for Propel(ORM)
include("generated-conf/config.php");

//Load helper classes.
require_once("Helpers/BasicHelper.php");
require_once("Helpers/ComponentsHelper.php");

//Load by namespace in thi project
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ ;

    $file = $base_dir . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});

ConfigHelper::loadFile('Config/default.php');

//Create new logger
LogHelper::init();

$i18n = new i18n();
$i18n->setForcedLang('cs');
$i18n->setFilePath('Languages/{LANGUAGE}.yml');
$i18n->setCachePath('./cache');
$i18n->init();

$language = $i18n->getAppliedLang();

//Add logger to Propel
Propel::getServiceContainer()->setLogger('defaultLogger', LogHelper::getLogger());

//Log request
$server_info = $_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'];
LogHelper::logMessage($server_info);
if($_SERVER['REQUEST_METHOD'] == "GET"){
	$params = var_export($_GET, true);
	LogHelper::logMessage('PARAMS: ' . $params);
}
else{
	$params = var_export($_POST, true);
	LogHelper::logMessage('PARAMS: ' . $params);
}
LogHelper::logMessage('HTTP_ACCEPT: ' . $_SERVER['HTTP_ACCEPT']);

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
	LogHelper::logMessage('ACTION: ' . $params['action']);
	$action = explode('.', $params['action']);
	$controller = "Controllers\\".$action[0]."Controller";
	$instance = new $controller;

	//Unset action so its not passed as first parameter probably use blacklist because of there can be some other params from route
	unset($params['action']);

	$instance->addParam('language', $language);

	call_user_func_array(array($instance, $action[1]), $params);
}
else{
	$failure = $router->getFailedRoute();

	//TODO render something nicer
	if ($failure->failedMethod()) {
		header("HTTP/1.0 405 Method Not Allowed");
		echo("405 Method Not Allowed");
		LogHelper::logMessage('405 FOUND');
	} elseif ($failure->failedAccept()) {
		header("HTTP/1.0 406 Not Acceptable");
		echo("406 Not Acceptable");
		LogHelper::logMessage('406 FOUND');
	} else {
		header("HTTP/1.0 404 Not Found");
		echo("404 Not found");
		LogHelper::logMessage('404 FOUND');
	}
}