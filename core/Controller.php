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
	
	public function __construct() {
		$this->actions();
	}
	
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
	
	protected function render($loc, $data = null) {
		$this->responseFormat = 'text/html';
		$location = explode('/', $loc);
		if ($this->_location[0] === null) {
			switch(count($location)) {
				case 1:
					$file = \Tree\Info\Config::$application['baseFolder'] . '/views/' . $this->_location[1] . '/' . $location[0] . '.php';
					break;
				case 2:
					$file = \Tree\Info\Config::$application['baseFolder'] . '/views/' . $location[0] . '/' . $location[1] . '.php';
					break;
				case 3:
					$file = \Tree\Info\Config::$application['baseFolder'] . '/modules/' . $location[0] . '/views/' . $location[1] . '/' . $location[2] . '.php';
					break;
			}
		} else {
			switch(count($location)) {
				case 1:
					$file = \Tree\Info\Config::$application['baseFolder'] . '/modules/' . $this->_location[0] . '/views/' . $this->_location[1] . '/' . $location[0] . '.php';
					break;
				case 2:
					$file = \Tree\Info\Config::$application['baseFolder'] . '/modules/' . $this->_location[0] . '/views/' . $location[0] . '/' . $location[1] . '.php';
					break;
				case 3:
					if ($location[0] === '') {
						$file = \Tree\Info\Config::$application['baseFolder'] . '/views/' . $location[1] . '/' . $location[2] . '.php';
					} else {
						$file = \Tree\Info\Config::$application['baseFolder'] . '/modules/' . $location[0] . '/views/' . $location[1] . '/' . $location[2] . '.php';
					}
					break;
			}
		}
		ob_start();
		ob_implicit_flush(false);
		if ($data !== null) {
			extract($data, EXTR_OVERWRITE);
		}
		require($file);
		$this->_data = ob_get_clean();
	}
	
	protected function redirect($loc, $params = null) {
		$this->responseCode = 302;
		$follow = '';
		if ($params !== null) {
			foreach($params as $key => $param) {
				$follow .= '&' . $key . '=' . $param;
			}
			$follow = '?' . substr($follow, 1, strlen($follow) - 1);
		}
		$location = explode('/', $loc);
		if ($this->_location[0] === null) {
			switch(count($location)) {
				case 1:
					$this->responseLocation = '/' . $this->_location[1] . '/' . $location[0] . $follow;
					break;
				case 2:
				case 3:
					$this->responseLocation = '/' . $loc . $follow;
					break;
			}
		} else {
			switch(count($location)) {
				case 1:
					$this->responseLocation = '/' . $this->_location[0] . '/' . $this->_location[1] . '/' . $location[0] . $follow;
					break;
				case 2:
					$this->responseLocation = '/' . $this->_location[0] . '/' . $location[0] . '/' . $location[1] . $follow;
					break;
				case 3:
					if ($location[0] === '') {
						$this->responseLocation = $loc . $follow;
					} else {
						$this->responseLocation = '/' . $loc . $follow;
					}
					break;
			}
		}

	}
	
}