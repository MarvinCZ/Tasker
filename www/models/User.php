<?php

class User{
	private $methods;
	private $data;
	private $changed;

	public function __construct($id){
		$row = DbWrapper::$database->fetchOne("SELECT * FROM users WHERE id=:id",array('id'=>$id));
		if(!$row){
			throw new Exception("Record wasnt found", 1);			
		}
		$this->methods = array();
		$this->changed = array();
		$this->data = $row;
		foreach ($row as $key => $value) {
			if($key == "id")
				continue;
			$get = function() use ($key){
				return $this->data[$key];
			};
			$set = function($var) use ($key){
				if($this->data[$key] != $var){
					$this->data[$key] = $var;
					array_push($this->changed, $key);
				}
			};
			$this->methods['get'.ucfirst($key)] =  \Closure::bind($get, $this, get_class());
			$this->methods['set'.ucfirst($key)] =  \Closure::bind($set, $this, get_class());
		}
	}

	private function createMethods(){
		
	}

	function __call($method, $args) {
		if(isset($this->methods[$method]) && is_callable($this->methods[$method])){
			return call_user_func_array($this->methods[$method], $args);
		}
		else
			throw new Exception("Method undefined: ".$method, 1);
  }

  public function save(){
  	if(empty($this->changed))
  		return;
  	$data = array_intersect_key($this->data, array_flip($this->changed));
  	$data['id'] = $this->data['id'];
  	$sql = "UPDATE users SET ";
  	foreach ($this->changed as $key) {
  		$sql .= $key."=:".$key.",";
  	}
  	$sql = substr($sql, 0, -1);
  	$sql .= " WHERE id=:id";
  	DbWrapper::$database->query($sql,$data);
  	$this->changed = array();
  }
}