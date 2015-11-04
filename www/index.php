<?php

use Propel\Runtime\Propel;
use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;


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

$user = UserQuery::create()->findPK(1);
//$category = new Category();
//$category->setName("Work");
//$category->setColor("4444ff");
//$category->setUser($user);
//$category->save();
$categories = CategoryQuery::create()->
	select('name')->
	filterByUser($user)->
	find();

$options = options_for_select($categories, -1);

if(isset($_GET['category'])){
	print_r($_GET['category']);
}
else{
	includeFile("views/template.phtml", array('options' => $options));
}