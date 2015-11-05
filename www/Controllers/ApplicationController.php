<?php

namespace Controllers;

use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;

//Parent controller
//Handles rendering, befor and after filter
abstract class ApplicationController{
	protected $params = array();

	protected $beforeFilters = array();
	protected $afterFilters = array();

	private $rendered = false;

	public function __construct(){
		$this->params['title'] = "Tasker";
		$this->beforeFilters['test'] = function(){
			$this->params['test'] = "test";
		};
	}

	protected function renderFileToTemplate($file, $params = array()){
		if($this->rendered)
			throw new Exception("Render was already called", 1);
			
		$par = array_merge($this->params, $params);

		includeFile("Views/template.phtml", array('params' => $par, 'inside' => "Views/".$file));

		$this->rendered = true;
	}

	protected function renderToTemplate($params = array()){
		if($this->rendered)
			throw new Exception("Render was already called", 1);
		
		$back = debug_backtrace()[1];
		$action = $back['function'];
		$controller = $back['class'];
		$controller = substr($controller, 12, strlen($controller) - 22);

		$par = array_merge($this->params, $params);

		includeFile("Views/template.phtml", array('params' => $par, 'inside' => "Views/".$controller."/".$action.".phtml"));

		$this->rendered = true;
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

		if(!$this->rendered){
			$back = debug_backtrace()[2];
			$action = $back['function'];
			$controller = $back['class'];
			$controller = substr($controller, 12, strlen($controller) - 22);

			includeFile("Views/template.phtml", array('params' => $this->params, 'inside' => "Views/".$controller."/".$action.".phtml"));
		}
	}
}