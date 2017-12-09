<?php

namespace App\Controller;
	
class TestController extends \Tree\Core\Controller {
	
	public function __construct() {
		
		$this->get('/index', function() {
			$this->render('index', [
				'name' => $this->params('name'),
				'id' => $this->params('id')
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
		
		$this->post('/test', function() {
			$this->render('example/default/index');
		});
		
		$this->put('/test', function() {
			$this->responseFormat = 'raw';
			$this->responseCode = '401';
			return 'Unauthorized';
		});
		
		$this->all('/test', function() {
			$name = $this->params('name');
			$this->redirect('example/index', ['name' => $name]);
		});
		
		$this->get('/module', function() {
			$this->redirect('sample/default/index');
		});
		
	}
	
}