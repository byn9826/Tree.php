<?php
	
class TestController extends Tree\Core\Controller {
	
	public function __construct() {
		
		$this->get('/index', function() {
			$this->render([
				'name' => $this->params('name')
			]);
		});
		
		$this->post('/index', function() {
			$params = $this->params();
			$this->return([
				'type' => 'get',
				'location' => '/index',
				'params' => $params
			]);
		});
			
		$this->delete('/index', function() {
			return 123;
		});
		
		$this->put('/test', function() {
			$this->responseFormat = 'raw';
			$this->responseCode = '401';
			return 'Unauthorized';
		});
		
		$this->all('/test', function() {
			$name = $this->params('name');
			if (isset($name)) {
				$this->redirect('index', ['name' => $name]);
			} else {
				$this->redirect('index');
			}
		});
		
	}
	
}