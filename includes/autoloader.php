<?php
function autoload($classname) {
	if (file_exists('classes/'.strtolower($classname).'.class.php')) {
		require_once('classes/'.strtolower($classname).'.class.php');
	}
}

spl_autoload_register('autoload');
?>