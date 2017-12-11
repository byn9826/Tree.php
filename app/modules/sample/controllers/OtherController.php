<?php

namespace Sample\Controller;
	
class OtherController extends \Tree\Core\Controller {
	
	public function actions() {
		
		$this->get('/index', function() {
			$this->render('default/index');
		});
		
	}
	
}