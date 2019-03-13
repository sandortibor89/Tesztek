<?php
clearstatcache();

spl_autoload_register(function($classname) {
	require_once ROOT_DIR.DS.str_replace('\\', DS, $classname).'.php';
});
?>