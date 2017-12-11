<?php

namespace Tree\Core;

class ModelFetcher {
	
	private $_tableName;
	private $_className;
	private $_primaryKey;
	private $_selectQuery = '';
	private $_whereQuery = '';
	private $_orderQuery = '';
	
	public function __construct($table, $key, $class) {
		$this->_tableName = $table;
		$this->_primaryKey = $key;
		$this->_className = $class;
	}
	
	public function select($conditions) {
		$this->_selectQuery = array_unique(array_merge($conditions, $this->_primaryKey));
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
		$instance = new $this->_className();
		$_db = \Tree\Core\Mysql::getInstance();
		$result = $_db->query($query);
		if (!empty($result)) {
			$instance->_dataStorage = $result[0];
			$instance->_mode = 'old';
			return $instance;
		}
	}
	
	public function all() {
		$query = $this->buildQuery();
		$models = [];
		$_db = \Tree\Core\Mysql::getInstance();
		$results = $_db->query($query);
		if (!empty($result)) {
			foreach($results as $result) {
				$instance = new $this->_className();
				$instance->_dataStorage = $result;
				$instance->_mode = 'old';
				array_push($models, $instance);
			}
			return $models;
		}
	}
	
	private function buildQuery() {
		$query = $this->_selectQuery === '' ?
			'SELECT * FROM ' . $this->_tableName :
			'SELECT ' . implode(', ', $this->_selectQuery) . ' FROM ' . $this->_tableName;
		if ($this->_whereQuery !== '') {
			$query .= ' WHERE';
			$params = [];
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