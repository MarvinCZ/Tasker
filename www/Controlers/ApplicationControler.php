<?php

namespace Controlers;

//Parent controler
//Handles rendering
//TODO: Before After Filtr
abstract class ApplicationControler{
	protected $title = "Tasker";

	protected function renderFileToTemplate($file, $params = array()){
		includeFile("Views/template.phtml", array('params' => $params, 'title' => $this->title, 'inside' => "Views/".$file));
	}

	protected function renderToTemplate($params = array()){
		$back = debug_backtrace()[1];
		$action = $back['function'];
		$controler = $back['class'];
		$controler = substr($controler, 11, strlen($controler) - 20);

		includeFile("Views/template.phtml", array('params' => $params, 'title' => $this->title, 'inside' => "Views/".$controler."/".$action.".phtml"));
	}
}