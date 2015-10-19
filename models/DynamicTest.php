<?php
class DynamicTest{
	private $methods;
	private $data;

	public function __construct($array){
		$this->methods = array();
		$this->data = $array;
		foreach ($array as $key => $value) {
			$get = function() use ($key){
				return $this->data[$key];
			};
			$set = function($var) use ($key){
				$this->data[$key] = $var;
			};
			$this->methods['get'.ucfirst($key)] =  \Closure::bind($get, $this, get_class());
			$this->methods['set'.ucfirst($key)] =  \Closure::bind($set, $this, get_class());
		}
	}

	function __call($method, $args) {
		if(isset($this->methods[$method]) && is_callable($this->methods[$method])){
			return call_user_func_array($this->methods[$method], $args);
		}
		else
			throw new Exception("Method undefined: ".$method, 1);
    }
}