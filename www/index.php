<?php

use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;
use PHPRouter\Config;

use Controllers\HomePageController;


mb_internal_encoding("UTF-8");

//Register composers autoload
require_once '../vendor/autoload.php';

//Load config for Propel(ORM)
include("generated-conf/config.php");

//Load every helper class.
//TODO: Make it more efficent
foreach (glob("Helpers/*.php") as $helper)
{
    require_once($helper);
}

//Load by namespace in thi project
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ ;

    $file = $base_dir . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});

//And the magic comes
$config = Config::loadFromFile(__DIR__.'/routes.yml');
$router = Router::parseConfig($config);
$router->matchCurrentRequest();