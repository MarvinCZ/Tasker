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

	protected $beforeFilters = array('filters' => array());
	protected $afterFilters = array('filters' => array());

	private $rendered = false;

	public function __construct(){
		$this->params['title'] = "Tasker";
		$this->addBeforeFilter("init_flashes");
		$this->addBeforeFilter("load_user");
	}

	private function init_flashes(){
		$this->params["flashes"] = isset($_SESSION['flashes']) ? $_SESSION['flashes'] : array();
		$_SESSION['flashes'] = array();
	}

	private function load_user(){
		if(isset($_SESSION['user'])){
			$this->params['user'] = UserQuery::create()->findPK(isset($_SESSION['user']));
			$this->params['user_logged'] = true;
		}
		else{
			$this->params['user_logged'] = false;
			$_SESSION['user'] = 1;
			$this->params['user'] = UserQuery::create()->findPK(isset($_SESSION['user']));
			$this->params['user_logged'] = true;
		}
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

	//Render just file
	protected function renderFile($file){
		incldeFile("Views/".$file, $this->params);
		$this->rendered = true;
	}

	protected function renderString($string){
		echo($string);
		$this->rendered = true;
	}

	//Render file with type (View/Controller/action.+$type)
	protected function renderType($type){
		$back = debug_backtrace()[1];
		$action = $back['function'];
		$controller = $back['class'];
		$controller = substr($controller, 12, strlen($controller) - 22);

		includeFile("Views/".$controller."/".$action.".".$type, $this->params);

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
		foreach ($this->beforeFilters['filters'] as $name) {
			$except = $this->beforeFilters[$name]['exeptions'];
			$includes = $this->beforeFilters[$name]['includes'];
			if(!(!empty($except) && in_array($action, $except)) &&
				!(!empty($includes) && !in_array($action, $includes))){
				$this->$name($action);
			}
		}
	}

	//Runs every after filter
	//Renders default view if nothing wasnt rendered yet
	protected function afterFilter($action){
		foreach ($this->afterFilters['filters'] as $name) {
			$except = $this->afterFilters[$name]['exeptions'];
			$includes = $this->afterFilters[$name]['includes'];
			if(!(!empty($except) && in_array($action, $except)) &&
				!(!empty($includes) && !in_array($action, $includes))){
				$this->$name($action);
			}
		}

		if(!$this->rendered){
			$back = debug_backtrace()[2];
			$action = $back['function'];
			$controller = $back['class'];
			$controller = substr($controller, 12, strlen($controller) - 22);

			includeFile("Views/template.phtml", array('params' => $this->params, 'inside' => "Views/".$controller."/".$action.".phtml"));
		}
	}

	public function addBeforeFilter($name){
		$this->beforeFilters["filters"][] = $name;
		$this->beforeFilters[$name] = array('exeptions' => array(), 'includes' => array());
	}

	public function addAfterFilter($name){
		$this->afterFilters["filters"][] = $name;
		$this->afterFilters[$name] = array('exeptions' => array(), 'includes' => array());
	}

	public function addBeforeFilterExeption($name, $exeption){
		$this->addFilterExeption($this->beforeFilters, "exeptions", $name, $exeption);
	}

	public function addBeforeFilterInclude($name, $include){
		$this->addFilterExeption($this->beforeFilters, "exeptions", $name, $include);
	}

	public function addAfterFilterExeption($name, $exeption){
		$this->addFilterExeption($this->afterFilters, "includes", $name, $exeption);
	}

	public function addAfterFilterInclude($name, $include){
		$this->addFilterExeption($this->afterFilters, "includes", $name, $include);
	}

	private function addFilterExeption(&$filter, $way, $name, $what){
		if(array_key_exists($name, $filter)){
			$filter[$name]['exeptions'] = array();
			$filter[$name]['includes'] = array();
			if(!is_array($what))
				$what = array($what);
			if(array_key_exists($way, $filter[$name]))
				$filter[$name][$way] = array_merge($filter[$name][$way], $what);
			else
				$filter[$name][$way] = $what;
		}
		else{
			throw new Exception("Filter does not exists");
		}
	}

	protected function addFlash($type, $message){
		array_push($_SESSION['flashes'], ['type' => $type, 'message' => $message]);
	}

	protected function addFlashNow($type, $message){
		array_push($this->params['flashes'], ['type' => $type, 'message' => $message]);
	}
}