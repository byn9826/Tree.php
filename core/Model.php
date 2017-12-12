<?php

namespace Tree\Core;

class Model {
	
	public $_dataStorage = [];
	public $_saveStorage = [];
	public $_mode = 'new';
	
	public function __construct() {}
	
	public static function fetch() {
		return new ModelFetcher(static::$table_name, static::$primary_key, static::class);
	}
	
	public function get($param) {
		return $this->_dataStorage[$param];
	}
	
	public function set($param, $value) {
		$this->_dataStorage[$param] = $value;
		array_push($this->_saveStorage, $param);
	}
	
	public function save() {
		if ($this->_mode === 'old') {
			$query = 'UPDATE ' . static::$table_name . ' SET';
			foreach($this->_saveStorage as $key => $param) {
				$query .= ' ' . $param . ' = ' . $this->_dataStorage[$param];
				if ($key + 1 !== count($this->_saveStorage)) {
					$query .= ',';
				}
			}
			$query .= $this->_buildWhereClause();
			$_db = $this->_executeQuery($query);
		} else {
			$columns = '';
			$values = '';
			foreach($this->_saveStorage as $key => $param) {
				$columns .= $param;
				$values .= $this->_dataStorage[$param];
				if ($key + 1 !== count($this->_saveStorage)) {
					$columns .= ', ';
					$values .= ', ';
				}
			}
			$query = 'INSERT INTO ' . static::$table_name . ' (' . $columns . ') VALUES (' . $values . ')'; 
			$_db = $this->_executeQuery($query);
			$this->_dataStorage[static::$primary_key] = $_db->insert_id;
			$this->_mode = 'old';
		}
		return $_db;
	}
	
	public function delete() {
		$query = 'DELETE FROM ' . static::$table_name . $this->_buildWhereClause();
		return $this->_executeQuery($query);
	}
	
	private function _buildWhereClause() {
		return ' WHERE ' . static::$primary_key . ' = ' . $this->_dataStorage[static::$primary_key];
	}
	
	private function _executeQuery($query) {
		$_db = \Tree\Core\Mysql::getInstance();
		$query = $_db->escape($query);
		$_db->query($query);
		return $_db;
	}
	
}