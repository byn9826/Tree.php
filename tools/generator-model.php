<?php

require(dirname(__dir__) . '/app/Config.php');

$template = file_get_contents(__dir__ . '/template/Model.php');
$template = str_replace(
	'BasePlaceholder', 
	\Tree\Info\Config::$application['baseNamespace'], 
	$template
);
$db_name = strtolower($argv[1]);
$template = str_replace(
	'ModelPlaceholder', 
	ucfirst($argv[1]), 
	$template
);
$template = str_replace(
	'NamePlaceholder', 
	$db_name, 
	$template
);

$result = file_put_contents(
	\Tree\Info\Config::$application['baseFolder'] . '/models/' . ucfirst($argv[1]) . '.php',
	$template
);

if ($result > 100) {
	var_dump('Success');
} else {
	var_dump('Failed');
}

