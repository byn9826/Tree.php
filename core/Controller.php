<?php

namespace Tree\Core;

class Controller {
	
	private $_getActions = [];
	private $_postActions = [];
	private $_putActions = [];
	private $_deleteActions = [];
	private $_allActions = [];
	
	private $_location;
	private $_params;
	
	public $_data;
	
	public $responseFormat = 'text/plain';
	public $responseCode = 200;
	public $responseLocation;
	
	private function __construct() {}
	
	public function setLocation($value) {
		$this->_location = $value;
	}
	
	public function setParams($params) {
		$this->_params = $params;
	}
	
	public function params($key = null) {
		return $key === null ? 
			$this->_params : 
			isset($this->_params[$key]) ? $this->_params[$key] : null;
	}
	
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
	
	protected function return($data) {
		$this->responseFormat = 'application/json';
		$this->_data = $data;
	}
	
	protected function render($params1, $params2 = null) {
		if ($params2 === null) {
			$data = $params1;
			$name = null;
		} else {
			$name = $params1;
			$data = $params2;
		}
		$this->responseFormat = 'text/html';
		if ($name === null) {
			if ($this->_location[0] === null) {
				$location = Server::$_root . '/app/view/' . $this->_location[1] . '/' . $this->_location[2] . '.php';
			}
		}
		ob_start();
		ob_implicit_flush(false);
		extract($data, EXTR_OVERWRITE);
		require($location);
		$this->_data = ob_get_clean();
	}
	
	protected function redirect($loc, $params = null) {
		$this->responseCode = 302;
		$follow = '';
		if ($params !== null) {
			$follow .= '?';
			foreach($params as $key => $param) {
				$follow .= $key . '=' . $param;
			}
		}
		$location = explode('/', $loc);
		switch(count($location)) {
			case 1:
				$this->responseLocation = $location[0] . $follow;
				break;
		}
	}
	
}