<?php
define('ROOT_DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('CL', 'clear');

function cl() { if (CL !== false) system(CL); }

require ROOT_DIR.DS.'autoload.php';

abstract class System {

	private static $map = [];

	private static function getInstance($class):self {
		if (!isset(self::$map[$class])) {
			self::$map[$class] = new $class();
		}
		return self::$map[$class];
	}

	public static function __callStatic($name, $arguments = []):self {
		$name = str_replace('_', '\\', $name);
		return self::getInstance($name);
	}
}

System::Machine_CoffeeMachine();
?>