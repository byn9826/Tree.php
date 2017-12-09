<?php

namespace App\Controller;

class ExampleController extends \Tree\Core\Controller {
	
	public function __construct() {
		
		$this->get('/index', function() {
			$this->render('test/index', [
				'name' => $this->params('name')
			]);
		});
		
	}
	
}