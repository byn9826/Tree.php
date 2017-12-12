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
		if(!in_array($this->_primaryKey, $conditions)) {
			array_push($conditions, $this->_primaryKey);
		}
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
		$query = $this->_buildQuery() . ' LIMIT 1';
		$instance = new $this->_className();
		$result = $this->_executeQuery($query);
		if (!empty($result)) {
			$instance->_dataStorage = $result[0];
			$instance->_mode = 'old';
			return $instance;
		}
	}
	
	public function all() {
		$query = $this->_buildQuery();
		$models = [];
		$result = $this->_executeQuery($query);
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
	
	private function _executeQuery($query) {
		$_db = \Tree\Core\Mysql::getInstance();
		$query = $_db->escape($query);
		return $_db->query($query);
	}
	
	private function _buildQuery() {
		$query = $this->_selectQuery === '' ?
			'SELECT * FROM ' . $this->_tableName :
			'SELECT ' . implode(', ', $this->_selectQuery) . ' FROM ' . $this->_tableName;
		if ($this->_whereQuery !== '') {
			$query .= ' WHERE';
			$params = [];
			foreach($this->_whereQuery as $key => $value) {
				$query .= $value === null ? 
					' ' . $key . ' IS NULL' : ' ' . $key . ' = ' . $value;
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