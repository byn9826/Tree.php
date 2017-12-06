<?php

class Dispatcher {
	
	private static $_instance;
	private $module;
	private $controller;
	private $action;
	private $method;
	private $params;
     
	private function __construct() {

	}
     
	public static function getInstance() {
		if (!self::$_instance || !self::$_instance instanceof self) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function dispatch($request, $response) {
		$path_loc = $request->server['path_info'];
		if ($path_loc === '/favicon.ico') {
			$this->pageNotFound($response);
			return;
		} else if ($path_loc === '/') {
			$this->controller = 'index';
			$this->action = 'index';
		} else {
			$path_info = explode('/', $path_loc);
			$level = count($path_info);
			switch ($level) {
				case 4:
					$this->module = $path_info[1];
					$this->controller = $path_info[2];
					$this->action = $path_info[3];
					break;
				case 3:
					$this->controller = $path_info[1];
					$this->action = $path_info[2];
					break;
				case 2:	
					$this->controller = $path_info[1];
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
		$controllerPath = $this->getControllerPath();
		$loader = Tree::load($controllerPath);
		if (!$loader) {
			$this->pageNotFound($response);
			return;
		} 
		$controllerName = ucfirst($this->controller);
		if (!class_exists($controllerName)) {
			$this->pageNotFound($response);
			return;
		}
		$controller = new $controllerName();
		$this->action = 'action' . ucfirst($this->action);
		if (!method_exists($controller, $this->action)) {
			$this->pageNotFound($response);
			return;
		} 
		$this->getRequestParams($request);
		$controller->params = $this->params;
		$controller->method = $this->method;
		ob_start();
		$action = $this->action;
		$controller->$action();
		$body = ob_get_contents();
		ob_clean();
		$response->status(200);
		$response->end($body);
		return;
	}
     
  protected function getControllerPath() {
		$path = __DIR__ . '/app';
		if ($this->module) {
			$path .= '/modules/' . strtolower($this->module);
		}
		$path .= '/' . strtolower($this->controller) . 'Controller.php';
		return $path;
	}
	
	protected function getRequestParams($request) {
		$this->method = $request->server['request_method'];
		$content_type = $request->header['content-type'];
    if ($this->method === 'GET') {
      $this->params = isset($request->get) ? $request->get : [];
    } else {
      if($content_type === 'application/json') {
        $this->params = json_decode($request->rawContent(), true);
      } else if ($content_type === 'application/x-www-form-urlencoded') {
        $this->params = isset($request->post) ? $request->post : [];
      } else {
				$this->params = [];
			}
    }
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