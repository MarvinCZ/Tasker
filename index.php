<?php
mb_internal_encoding("UTF-8");

$array = get_defined_vars();

foreach (glob("helpers/*.php") as $helper)
{
    include($helper);
}

include('models/DynamicTest.php');
include('models/DbWrapper.php');
include('models/User.php');

$dt = new DynamicTest(array('b'=>'test'));
$dt->setB("testovani");
$dt->b = "test";

DbWrapper::connect();

$options = options_for_select(array('School', 'Work', 'Personal', 'Other'),'Personal');


includeFile("views/template.phtml", array('test'=>$dt, 'array'=>$_SERVER['REQUEST_URI'], 'options' => $options));