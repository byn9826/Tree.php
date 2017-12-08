<?php

namespace Tree\Core;

class Controller {
	
	private $_getActions = [];
	private $_postActions = [];
	private $_putActions = [];
	private $_deleteActions = [];
	private $_allActions = [];
	
	public $params;
	
	private function __construct() {}
	
	public function callAction($action, $type) {
		switch($type) {
			case 'GET':
				if (isset($this->_getActions[$action])) {
					return $this->_getActions[$action]();
				}
				break;
			case 'POST':
				if (isset($this->_postActions[$action])) {
					return $this->_postActions[$action]();
				}
				break;
			case 'PUT':
				if (isset($this->_putActions[$action])) {
					return $this->_putActions[$action]();
				}
				break;
			case 'DELETE':
				if (isset($this->_deleteActions[$action])) {
					return $this->_deleteActions[$action]();
				}
				break;
			default:
				return false;
		}
		return isset($this->_allActions[$action]) ? $this->_allActions[$action]() : false;
	}
	
	protected function get($action, $method) {
		$this->_getActions[$action] = $method;
	}
	
	protected function post($action, $method) {
		$this->_postActions[$action] = $method;
	}
	
	protected function put($action, $method) {
		$this->_putActions[$action] = $method;
	}
	
	protected function delete($action, $method) {
		$this->_deleteActions[$action] = $method;
	}
	
	protected function all($action, $method) {
		$this->_allActions[$action] = $method;
	}
	
}