<?php
mb_internal_encoding("UTF-8");

$array = get_defined_vars();

foreach (glob("helpers/*.php") as $helper)
{
    include($helper);
}

include('models/DynamicTest.php');

$dt = new DynamicTest(array('b'=>'test'));
$dt->setB("testovani");
$dt->b = "test";


includeFile("views/template.phtml", array('test'=>$dt, 'array'=>$_SERVER['REQUEST_URI']));