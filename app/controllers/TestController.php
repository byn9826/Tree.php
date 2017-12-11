<?php

namespace App\Controller;

use \App\Model\Testing;
	
class TestController extends \Tree\Core\Controller {
	
	public function actions() {
		
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
			$this->render('sample/default/index');
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
		
		$this->get('/db', function() {
			$test = Testing::fetch()->where(['name' => '123'])->order(['name' => 'ASC'])->select(['name'])->one();
			$this->return([
				'data' => $test->name
			]);
		});
		
	}
	
}