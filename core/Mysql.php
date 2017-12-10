<?php 

namespace Tree\Core;

class Mysql {

	private static $_db = null;

  public function getInstance() {
		if (self::$_db === null) {
			$_db = new \Swoole\Coroutine\MySQL();
			$host = \Tree\Info\Config::$mysql['host'];
			$port = \Tree\Info\Config::$mysql['port'];
			$_db->connect([
				'host' => isset($host) ? $host : '127.0.0.1',
				'port' => isset($port) ? $port : 3306,
				'user' => \Tree\Info\Config::$mysql['username'],
				'password' => \Tree\Info\Config::$mysql['password'],
				'database' => \Tree\Info\Config::$mysql['database']
			]);
			self::$_db = $_db;
		}
		return self::$_db;
  }

}