<?php

namespace Sample\Controller;
	
class OtherController extends \Tree\Core\Controller {
	
	public function __construct() {
		
		$this->get('/index', function() {
			$this->render('default/index');
		});
		
	}
	
}