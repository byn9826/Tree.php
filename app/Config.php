<?php

namespace Tree\Info;

class Config {
	
	public static $application = [
		'baseNamespace' => 'App',
		'baseFolder' => __DIR__,
		'env' => true
	];
	
	public static $mysql = [
		'host' => '127.0.0.1',
		'port' => 3306,
		'username' => 'root',
		'password' => '123123',
		'database' => 'tree',
	];
	
}