<?php 

namespace Tree\Core;

class Server {

	private static $_core;
	
	public static $_root;

	public function __construct($root) {
		self::$_core = new \swoole\http\server('127.0.0.1', '9501');
		self::$_root = $root;
	}

  public function run() {
		self::$_core->set([
			'worker_num'=>2
		]);
		self::$_core->on('request', function($request, $response) {
			Router::getInstance()->dispatch($request, $response);
  	});
		self::$_core->start();
  }

}