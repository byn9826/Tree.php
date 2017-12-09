<?php

namespace Sample\Controller;
	
class DefaultController extends \Tree\Core\Controller {
	
	public function __construct() {
		
		$this->get('/index', function() {
			$this->render('index');
		});
		
		$this->get('/leave-module', function() {
			$this->render('/test/index');
		});
		
		$this->get('/other-module', function() {
			$this->render('etc/index/index');
		});
		
		$this->get('/inner-redirect', function() {
			$this->redirect('index');
		});
		
		$this->get('/other-redirect', function() {
			$this->redirect('other/index');
		});
		
		$this->get('/another-redirect', function() {
			$this->redirect('etc/index/index');
		});
		
		$this->get('/outer-redirect', function() {
			$this->redirect('/test/index');
		});
	
	}
	
}