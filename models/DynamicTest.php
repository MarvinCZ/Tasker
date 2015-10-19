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

  public function __get($var){
  	if(array_key_exists($var, $this->data)){
  		return $this->data[$var];
  	}
  	else{
  		throw new Exception("Variable: " . $var . " does not exists", 1);  		
  	}
  }

  public function __set($var, $value){
  	if(array_key_exists($var, $this->data)){
			$this->data[$var] = $value;
  	}
  	else{
  		throw new Exception("Variable: " . $var . " does not exists", 1);  		
  	}
  }
}