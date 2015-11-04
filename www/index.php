<?php

use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;
use PHPRouter\Config;


mb_internal_encoding("UTF-8");

require_once '../vendor/autoload.php';

include("generated-conf/config.php");

foreach (glob("Helpers/*.php") as $helper)
{
    include($helper);
}

spl_autoload_register(function ($class) {
    $base_dir = __DIR__ ;

    $file = $base_dir . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$config = Config::loadFromFile(__DIR__.'/routes.yml');
$router = Router::parseConfig($config);
$router->matchCurrentRequest();