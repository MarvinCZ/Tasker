<?php

namespace Controlers;

use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;

//Parent controler
//Handles rendering, befor and after filter
abstract class ApplicationControler{
	protected $params = array();

	protected $beforeFilters = array();
	protected $afterFilters = array();

	public function __construct(){
		$this->params['title'] = "Tasker";
		$this->beforeFilters['test'] = function(){
			$this->params['test'] = "test";
		};
	}

	protected function renderFileToTemplate($file, $params = array()){
		$par = array_merge($this->params, $params);

		includeFile("Views/template.phtml", array('params' => $par, 'inside' => "Views/".$file));
	}

	protected function renderToTemplate($params = array()){
		$back = debug_backtrace()[1];
		$action = $back['function'];
		$controler = $back['class'];
		$controler = substr($controler, 11, strlen($controler) - 20);

		$par = array_merge($this->params, $params);

		includeFile("Views/template.phtml", array('params' => $par, 'inside' => "Views/".$controler."/".$action.".phtml"));
	}

	public function __call($method,$arguments) {
		if(method_exists($this, $method)) {
			$this->beforeFilter();
			call_user_func_array(array($this,$method),$arguments);
			$this->afterFilter();
		}
	}

	protected function beforeFilter(){
		foreach ($this->beforeFilters as $name => $method) {
			$method();
		}
	}

	protected function afterFilter(){
		foreach ($this->afterFilters as $name => $method) {
			$method();
		}
	}
}