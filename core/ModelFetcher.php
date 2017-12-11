<?php

namespace Tree\Core;

class ModelFetcher {
	
	private $_tableName;
	private $_className;
	private $_selectQuery = '';
	private $_whereQuery = '';
	private $_orderQuery = '';
	
	public function __construct($table, $class) {
		$this->_tableName = $table;
		$this->_className = $class;
	}
	
	public function select($conditions) {
		$this->_selectQuery = $conditions;
		return $this;
	}
	
	public function where($conditions) {
		$this->_whereQuery = $conditions;
		return $this;
	}
	
	public function order($conditions) {
		$this->_orderQuery = $conditions;
		return $this;
	}
	
	public function one() {
		$query = $this->buildQuery() . ' LIMIT 1';
		$_db = \Tree\Core\Mysql::getInstance();
		$result = $_db->query($query)[0];
		$instance = new $this->_className();
		foreach($result as $k => $s) {
			$instance->$k = $s;
		}
		return $instance;
	}
	
	public function all() {
		$query = $this->buildQuery();
		$_db = \Tree\Core\Mysql::getInstance();
		$results = $_db->query($query);
		$data = [];
		foreach($results as $result) {
			$instance = new $this->_className();
			foreach($result as $k => $s) {
				$instance->$k = $s;
			}
			array_push($data, $instance);
		}
		return $data;
	}
	
	private function buildQuery() {
		$query = $this->_selectQuery === '' ?
			'SELECT * FROM ' . $this->_tableName :
			'SELECT ' . implode(', ', $this->_selectQuery) . ' FROM ' . $this->_tableName;
		if ($this->_whereQuery !== '') {
			$query .= ' WHERE';
			foreach($this->_whereQuery as $key => $value) {
				$query .= $value === null ? 
					//*Temporarily use addslashes for Mysql Escape
					' ' . $key . ' IS NULL' : ' ' . $key . ' = ' . addslashes($value);
			}
		}
		if ($this->_orderQuery !== '') {
			$query .= ' ORDER BY';
			$i = 0;
			foreach($this->_orderQuery as $key => $value) {
				$i++;
				$query .= $i === count($this->_orderQuery) ? 
					' ' . $key . ' ' . $value : ' ' . $key . ' ' . $value . ',';
			}
		}
		return $query;
	}
	
}