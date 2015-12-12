<?php

namespace Helpers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogHelper{
	private static $log;
	private static $handler;

	public static function init(){
		self::$handler = new StreamHandler('app.log', Logger::INFO);
		self::$handler->setFormatter(new \Monolog\Formatter\LineFormatter("[%datetime%] %level_name%: %message%\n"));
		self::$log = new Logger('defaultLogger');
		self::$log->pushHandler(self::$handler);
	}

	public static function getLogger(){
		return self::$log;
	}

	public static function logMessage($message){
		self::$log->addInfo($message);
	}
}