<?php

require(dirname(__dir__) . '/app/Config.php');

$template = file_get_contents(__dir__ . '/template/Controller.php');
$template = str_replace(
	'BasePlaceholder', 
	\Tree\Info\Config::$application['baseNamespace'], 
	$template
);
$name = ucfirst($argv[1]) . 'Controller';
$template = str_replace(
	'ControllerPlaceholder', 
	$name, 
	$template
);

$result = file_put_contents(
	\Tree\Info\Config::$application['baseFolder'] . '/controllers/' . $name . '.php',
	$template
);

if ($result > 100) {
	var_dump('Success');
} else {
	var_dump('Failed');
}