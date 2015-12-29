<?php

namespace Controllers;

use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;
use Helpers\LogHelper;

/**
 * Basic controller
 * Handles rendering, before and after filters
 * @author Martin Bruna
 * @package Controllers
 */
abstract class ApplicationController{
	protected $params = array();
	protected $template = "template.phtml";

	protected $beforeFilters = array('filters' => array());
	protected $afterFilters = array('filters' => array());

	private $rendered = false;

	public function __construct(){
		$this->params['title'] = "Tasker";
		$this->addBeforeFilter("init_flashes");
		$this->addBeforeFilter("load_user");
		$this->addBeforeFilter("is_logged");
	}

	/**
	 * Move flashes from session to params
	 * @return array with flashes
	 */
	private function init_flashes(){
		$this->params["flashes"] = isset($_SESSION['flashes']) ? $_SESSION['flashes'] : array();
		$_SESSION['flashes'] = array();
		return $this->params["flashes"];
	}

	/**
	 * Load logged user into params
	 * @return mixed(Models\User, null) logged user
	 */
	private function load_user(){
		if(isset($_SESSION['user'])){
			$this->params['user_logged'] = true;
			return $this->params['user'] = UserQuery::create()->findPK($_SESSION['user']);
		}
		$this->params['user_logged'] = false;
	}

	/**
	 * redirect if user is not logged
	 */
	protected function is_logged(){
		if(!$this->params['user_logged']){
			redirectTo('/');
		}
	}

	/**
	 * In case you want to render something else than View/Controller/action.phtml into a template
	 * @param  String file to render
	 */
	protected function renderFileToTemplate($file){
		if($this->rendered)
			throw new Exception("Render was already called", 1);

		includeFile("Views/".$this->template, array('params' => $this->params, 'inside' => "Views/".$file));
		LogHelper::logMessage("Rendering file: " . $file);

		$this->rendered = true;
	}

	/**
	 * Theres no need to call this function its preformed by default
	 * Renders View/Controller/action.phtml into a template
	 */
	protected function renderToTemplate(){
		if($this->rendered)
			throw new Exception("Render was already called", 1);
		
		$back = debug_backtrace()[1];
		$action = $back['function'];
		$controller = $back['class'];
		$controller = substr($controller, 12, strlen($controller) - 22);

		$file = $controller."/".$action.".phtml";
		includeFile("Views/".$this->template, array('params' => $this->params, 'inside' => "Views/".$file));
		LogHelper::logMessage("Rendering file: " . $file);

		$this->rendered = true;
	}

	/**
	 * Renders file
	 * @param  String file to render
	 */
	protected function renderFile($file){
		includeFile("Views/".$file, $this->params);
		LogHelper::logMessage("Rendering file: " . $file);
		$this->rendered = true;
	}

	/**
	 * Renders text
	 * @param  String text to render
	 */
	protected function renderString($string){
		echo($string);
		LogHelper::logMessage("Rendering string: " . $string);
		$this->rendered = true;
	}

	/**
	 * Renders default file with extension
	 * @param  string extension of file
	 */
	protected function renderType($type){
		$back = debug_backtrace()[1];
		$action = $back['function'];
		$controller = $back['class'];
		$controller = substr($controller, 12, strlen($controller) - 22);

		$file = $controller."/".$action.".".$type;
		includeFile("Views/".$file, $this->params);
		LogHelper::logMessage("Rendering file: " . $file);

		$this->rendered = true;
	}

	public function __call($method,$arguments) {
		if(method_exists($this, $method)) {
			$this->beforeFilter($method);
			call_user_func_array(array($this,$method),$arguments);
			$this->afterFilter($method);
		}
	}

	/**
	 * Runs every before filter
	 * @param  string called action
	 */
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

	/**
	 * Runs every after filter
	 * Renders default view if nothing wasn't rendered yet
	 * @param  string called action
	 */
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

			$file = $controller."/".$action.".phtml";
			includeFile("Views/".$this->template, array('params' => $this->params, 'inside' => "Views/".$file));
			LogHelper::logMessage("Rendering file: " . $file);
		}
	}

	/**
	 * Add name of function, whitch should be called before action
	 * @param string name of function
	 */
	public function addBeforeFilter($name){
		$this->beforeFilters["filters"][] = $name;
		$this->beforeFilters[$name] = array('exeptions' => array(), 'includes' => array());
	}

	/**
	 * Add name of function, whitch should be called after action
	 * @param string name of function
	 */
	public function addAfterFilter($name){
		$this->afterFilters["filters"][] = $name;
		$this->afterFilters[$name] = array('exeptions' => array(), 'includes' => array());
	}

	/**
	 * Don't call function before action
	 * @param string name of function
	 * @param string name of action
	 */
	public function addBeforeFilterExeption($name, $exeption){
		$this->addFilterExeption($this->beforeFilters, "exeptions", $name, $exeption);
	}

	/**
	 * Call function before allowed actions only
	 * @param string name of function
	 * @param string name of action
	 */
	public function addBeforeFilterInclude($name, $include){
		$this->addFilterExeption($this->beforeFilters, "exeptions", $name, $include);
	}

	/**
	 * Don't call function after action
	 * @param string name of function
	 * @param string name of action
	 */
	public function addAfterFilterExeption($name, $exeption){
		$this->addFilterExeption($this->afterFilters, "includes", $name, $exeption);
	}

	/**
	 * Call function after allowed actions only
	 * @param string name of function
	 * @param string name of action
	 */
	public function addAfterFilterInclude($name, $include){
		$this->addFilterExeption($this->afterFilters, "includes", $name, $include);
	}

	/**
	 * Create exeptions on filters
	 * @param array functions
	 * @param string (exeptions, includes)
	 * @param string name of function
	 * @param string name of action
	 */
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

	/**
	 * Save flash to session to be displayed after redirect
	 * @param string type of flash (success, error, warning)
	 * @param string message, which will be displayed
	 */
	protected function addFlash($type, $message){
		array_push($_SESSION['flashes'], ['type' => $type, 'message' => $message]);
	}

	/**
	 * Display flash in this response
	 * @param string type of flash (success, error, warning)
	 * @param string message, which will be displayed
	 */
	protected function addFlashNow($type, $message){
		array_push($this->params['flashes'], ['type' => $type, 'message' => $message]);
	}
}