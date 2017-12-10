<?php

namespace Tree\Core;

class Router {

	private static $_instance;

	private $module;
	private $controller;
	private $action;
	private $type;
	private $location;
     
	private function __construct() {}
     
	public static function getInstance() {
		if (!self::$_instance || !self::$_instance instanceof self) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function dispatch($request, $response) {
		$pathLoc = $request->server['path_info'];
		if ($pathLoc === '/favicon.ico') {
			$this->pageNotFound($response);
			return;
		} else if ($pathLoc === '/') {
			$this->controller = 'index';
			$this->action = 'index';
		} else {
			$pathInfo = explode('/', $pathLoc);
			$level = count($pathInfo);
			switch ($level) {
				case 4:
					$this->module = $pathInfo[1];
					$this->controller = $pathInfo[2];
					$this->action = $pathInfo[3];
					break;
				case 3:
					$this->module = null;
					$this->controller = $pathInfo[1];
					$this->action = $pathInfo[2];
					break;
				case 2:	
					$this->module = null;
					$this->controller = $pathInfo[1];
					$this->action = 'index';
					break;
				default:
					$this->BadRequest($response);
					return;
			}
		}
		if (!$this->controller) {
			$this->pageNotFound($response);
			return;
		}
		$this->location = [$this->module, $this->controller];
		$controllerPath = $this->getControllerPath();
		$loader = Loader::load($controllerPath);
		if (!$loader) {
			$this->pageNotFound($response);
			return;
		}
		$realClass = $this->module === null ? 
			'\\' . \Tree\Info\Config::$application['baseNamespace'] . '\\Controller\\' : 
			'\\' . ucfirst($this->module) . '\\Controller\\';
		$realClass .= $this->controller;
		if (!class_exists($realClass)) {
			$this->pageNotFound($response);
			return;
		}
		$controller = new $realClass();
		$this->action = '/' . $this->action;
		$this->type = $request->server['request_method'];
		$controller->setParams($this->getRequestParams($request));
		$controller->setLocation($this->location);
		$result = $controller->callAction($this->action, $this->type);
		if ($result === false) {
			$this->pageNotFound($response);
			return;
		}
		$response->header('Content-Type', $controller->responseFormat);
		$response->status($controller->responseCode);
		if ($controller->responseFormat === 'application/json') {
			$response->end(json_encode($controller->_data));
			return;
		}
		if ($controller->responseFormat === 'text/html') {
			$response->end($controller->_data);
			return;
		}
		if (isset($controller->responseLocation)) {
			$response->header('Location', $controller->responseLocation);
			return;
		}
		$response->end($result);
		return;
	}
     
  protected function getControllerPath() {
		$this->controller = ucfirst($this->controller) . 'Controller';
		$path = \Tree\Info\Config::$application['baseFolder'];
		if ($this->module !== null) {
			$path .= '/modules/' . $this->module;
		}
		$path .= '/controllers/' . $this->controller . '.php';
		return $path;
	}
	
	protected function getRequestParams($request) {
    if ($this->type === 'GET') {
      return isset($request->get) ? $request->get : [];
    }
		if ($request->header['content-type'] === 'application/json') {
			return json_decode($request->rawContent(), true);
		}
			return isset($request->post) ? $request->post : [];
	}
	
	public function pageNotFound($response) {
		$response->status(404);
		$response->end('Page not found');
	}
	
	public function BadRequest($response) {
		$response->status(400);
		$response->end('Bad Request');
	}
	
}