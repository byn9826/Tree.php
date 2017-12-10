<?php

namespace Tree\Core;

class Model {
	
	private $_table = null;
	
	public function __construct() {
		$this->init();
	}
	
	protected function useTable($table) {
		$this->_table = $table;
	}
	
	public function fetchAll($conditions) {
		$_db = \Tree\Core\Mysql::getInstance();
		$instance = new static();
		$cmd = 'SELECT * FROM ' . $instance->_table;
		$result = $_db->query($cmd);
		return $result;
	}
	
}