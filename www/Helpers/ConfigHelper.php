<?php

namespace Helpers;

class ConfigHelper{
	private static $configs = array();
	public static function loadFile($file){
		$array = include($file);
		if(is_array($array)){
			self::$configs = array_merge(self::$configs, $array);
		}
	}

	public static function getValue($path){
		return readArray(self::$configs, $path);
	}

}
function readArray($array, $path){
	if(($pos = strpos($path, '.')) !== false){
		$key = substr($path, 0, $pos);
		$restOfKey = substr($path, $pos + 1);
		if(!is_array($array) || !array_key_exists($key,$array))
			return null;
		return readArray($array[$key], $restOfKey);
	} 
	else{
		$key = $path;
		if(!is_array($array) || !array_key_exists($key,$array))
			return null;
		return $array[$key];
	}
}