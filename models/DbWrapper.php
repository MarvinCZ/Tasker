<?php

class DbWrapper{
	private $db;
	public static $database;

	public static function connect(){
		self::$database = new DbWrapper('mysql:host=127.0.0.1;dbname=marvins_db','root','mojeheslo');
	}

	public static function disconnect(){
		self::$database = null;
	}

	private function __construct($connection_str, $user, $pass){
		$this->db = new PDO($connection_str, $user, $pass);
		$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$this->db->exec("set names utf8");
	}
	
	public function fetchAll($query, $arr = array()){
		$this->logQuery($query, $arr);
		$q = $this->db->prepare($query);
		$q->execute($arr);
		return $q->fetchAll();
	}
	
	public function fetchOne($query, $arr = array()){
		$this->logQuery($query, $arr);
		$q = $this->db->prepare($query);
		$q->execute($arr);
		return $q->fetch();
	}
	
	public function query($query, $arr = array()){
		$this->logQuery($query, $arr);
		$q = $this->db->prepare($query);
		$q->execute($arr);
		return $q->rowCount();
	}

	private function logQuery($query, $params){
		$log = date("Y-m-d H:i:s") . " " . $query . " (";
		foreach ($params as $key => $value) {
			$log .= $key . "-" . $value . ',';
		}
  		$log = substr($log, 0, -1);
  		$log .= ")\n";
		file_put_contents('log.txt', $log, FILE_APPEND | LOCK_EX);		
	}
}