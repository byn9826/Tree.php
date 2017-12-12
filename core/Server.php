<?php 

namespace Tree\Core;

class Server {

	private static $_core;

	public function __construct() {
		self::$_core = new \swoole\http\server('127.0.0.1', '9501');
	}

  public function run() {
		self::$_core->set(\Tree\Info\Config::$server);
		self::$_core->on('request', function($request, $response) {
			Loader::loadModels();
			Router::getInstance()->dispatch($request, $response);
  	});
		self::$_core->start();
  }

}