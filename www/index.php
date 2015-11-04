<?php

use Propel\Runtime\Propel;


mb_internal_encoding("UTF-8");

require_once '../vendor/autoload.php';

include("generated-conf/config.php");

$array = get_defined_vars();

foreach (glob("helpers/*.php") as $helper)
{
    include($helper);
}
foreach (glob("models/*.php") as $model)
{
    include($model);
}

//$u = new User();
//$u->setNick("test");
//$u->setEmail("mbrunas.p@gmail.com");
//$u->setPassword("hash");
//$u->save();

$options = options_for_select(array('School', 'Work', 'Personal', 'Other'),'Personal');

if(isset($_GET['category'])){
	print_r($_GET['category']);
}
else{
	includeFile("views/template.phtml", array('options' => $options));
}