<?php

use Propel\Runtime\Propel;
use Models\User;
use Models\UserQuery;


mb_internal_encoding("UTF-8");

require_once '../vendor/autoload.php';

include("generated-conf/config.php");

foreach (glob("helpers/*.php") as $helper)
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

$options = options_for_select(array('School', 'Work', 'Personal', 'Other'),'Personal');

if(isset($_GET['category'])){
	print_r($_GET['category']);
}
else{
	includeFile("views/template.phtml", array('options' => $options));
}