<?php

require(__DIR__ . '/core/Server.php');
require(__DIR__ . '/core/Mysql.php');
require(__DIR__ . '/core/Loader.php');
require(__DIR__ . '/core/Router.php');
require(__DIR__ . '/core/Controller.php');
require(__DIR__ . '/core/Model.php');

require(__DIR__ . '/app/Config.php');

$http = new Tree\Core\Server();
$http->run();