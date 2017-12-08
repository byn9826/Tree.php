<?php
	
class TestController extends Tree\Core\Controller {
	
	public function __construct() {
		
		$this->all('/index', function() {
			var_dump($this->params);
		});
		
	}
}