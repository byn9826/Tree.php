<?php

namespace Etc\Controller;
	
class IndexController extends \Tree\Core\Controller {
	
	public function actions() {
		
		$this->get('/index', function() {
			$this->render('sample/default/index');
		});
	
	}
	
}