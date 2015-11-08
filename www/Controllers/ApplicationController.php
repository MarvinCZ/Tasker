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
	}

	//In case you want to render something else than View/Controller/action.phtml into a template
	protected function renderFileToTemplate($file){
		if($this->rendered)
			throw new Exception("Render was already called", 1);

		includeFile("Views/template.phtml", array('params' => $this->params, 'inside' => "Views/".$file));

		$this->rendered = true;
	}

	//Theres no need to call this function its preformed by default
	//Renders View/Controller/action.phtml into a template
	protected function renderToTemplate(){
		if($this->rendered)
			throw new Exception("Render was already called", 1);
		
		$back = debug_backtrace()[1];
		$action = $back['function'];
		$controller = $back['class'];
		$controller = substr($controller, 12, strlen($controller) - 22);

		includeFile("Views/template.phtml", array('params' => $this->params, 'inside' => "Views/".$controller."/".$action.".phtml"));

		$this->rendered = true;
	}

	public function __call($method,$arguments) {
		if(method_exists($this, $method)) {
			$this->beforeFilter($method);
			call_user_func_array(array($this,$method),$arguments);
			$this->afterFilter($method);
		}
	}

	//Runs every before filter
	protected function beforeFilter($action){
		foreach ($this->beforeFilters as $name => $method) {
			$method($action);
		}
	}

	//Runs every after filter
	//Renders default view if nothing wasnt rendered yet
	protected function afterFilter($action){
		foreach ($this->afterFilters as $name => $method) {
			$method($action);
		}

		if(!$this->rendered){
			$back = debug_backtrace()[2];
			$action = $back['function'];
			$controller = $back['class'];
			$controller = substr($controller, 12, strlen($controller) - 22);

			includeFile("Views/template.phtml", array('params' => $this->params, 'inside' => "Views/".$controller."/".$action.".phtml"));
		}
	}

	public function addBeforeFilter(callable $function, $name = null){
		if($name){
			if(array_key_exists($name, $this->beforeFilters)){
				throw new Exception("Before filter already exists");
			}
			$this->beforeFilters[$name] = $function;
		}
		else{
			array_push($this->beforeFilters, $function);
		}
	}

	public function addAfterFilter(callable $function, $name = null){
		if($name){
			if(array_key_exists($name, $this->afterFilters)){
				throw new Exception("After filter already exists");
			}
			$this->beforeFilters[$name] = $function;
		}
		else{
			array_push($this->afterFilters, $function);
		}
	}
}