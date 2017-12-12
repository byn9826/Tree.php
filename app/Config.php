<?php

namespace Tree\Info;

class Config {
	
	//Mysql client for model
	public static $mysql = [
		'host' => '127.0.0.1',
		'port' => 3306,
		'username' => 'root',
		'password' => '123123',
		'database' => 'tree',
	];
	
	//Base settings
	public static $application = [
		'baseNamespace' => 'App',
		'baseFolder' => __DIR__,
		'env' => true
	];
	
	//Options for set up the server
	public static $server = [
		//CPU number
		'Reactor' => 1,
		//1 - 4 * CPU number
		'worker_num' => 2,
		'dispatch_mode' => 1,
		//Set to 1 to daemonize server, log file to app.log
		'daemonize' => 0,
		'log_file' => './app.log',
		//serve for static file
		'document_root' => './public',
		'enable_static_handler' => true
	];
	
}